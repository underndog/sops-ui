package handler

import (
	"github.com/aws/aws-sdk-go-v2/aws"
	"github.com/getsops/sops/v3"
	"github.com/getsops/sops/v3/aes"
	"github.com/getsops/sops/v3/cmd/sops/common"
	"github.com/getsops/sops/v3/decrypt"
	"github.com/getsops/sops/v3/keyservice"
	sopsKMS "github.com/getsops/sops/v3/kms"
	"github.com/getsops/sops/v3/stores/yaml"
	"github.com/labstack/echo/v4"
	"io"
	"net/http"
	"sops-guardians/helper"
	"sops-guardians/log"
	"sops-guardians/model"
	"time"
)

type FileHandler struct {
}

func (f *FileHandler) HandlerFileEncrypted(c echo.Context) error {
	name := c.FormValue("name")
	log.Debug("filename is : ", name)
	kmsKeyARN := c.FormValue("kms-arn")
	log.Debug("KMS is : ", kmsKeyARN)
	yamlFile, err := c.FormFile("yaml-file")
	if err != nil {
		return c.JSON(http.StatusBadRequest, map[string]interface{}{
			"status_code": http.StatusBadRequest,
			"message":     err.Error(),
			"data":        nil,
		})
	}

	file, err := yamlFile.Open()
	if err != nil {
		return c.JSON(http.StatusInternalServerError, map[string]interface{}{
			"status_code": http.StatusInternalServerError,
			"message":     err.Error(),
			"data":        nil,
		})
	}
	defer file.Close()

	yamlContent, err := io.ReadAll(file)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, map[string]interface{}{
			"status_code": http.StatusInternalServerError,
			"message":     err.Error(),
			"data":        nil,
		})
	}

	// Define encryption context
	encryptionContext := map[string]*string{
		"Purpose": aws.String("SOPS encryption"),
	}

	// Load plain YAML content
	store := yaml.Store{}
	branches, err := store.LoadPlainFile(yamlContent)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, map[string]interface{}{
			"status_code": http.StatusInternalServerError,
			"message":     err.Error(),
			"data":        nil,
		})
	}

	// Create a new KMS master key
	masterKey := &sopsKMS.MasterKey{
		Arn:               kmsKeyARN,
		EncryptionContext: encryptionContext,
		CreationDate:      time.Now(),
	}

	// Create the SOPS tree with metadata
	tree := sops.Tree{
		Branches: branches,
		Metadata: sops.Metadata{
			KeyGroups: []sops.KeyGroup{
				{masterKey},
			},
			EncryptedRegex: "^(data|stringData)$",
		},
	}

	// Generate the data key with key services
	dataKey, errs := tree.GenerateDataKeyWithKeyServices(
		[]keyservice.KeyServiceClient{keyservice.NewLocalClient()},
	)
	if len(errs) > 0 {
		errorMessages := make([]string, len(errs))
		for i, e := range errs {
			errorMessages[i] = e.Error()
		}
		return c.JSON(http.StatusInternalServerError, map[string]interface{}{
			"status_code": http.StatusInternalServerError,
			"message":     "Failed to generate data key",
			"errors":      errorMessages,
			"data":        nil,
		})
	}

	// Encrypt the SOPS tree
	err = common.EncryptTree(common.EncryptTreeOpts{
		DataKey: dataKey,
		Tree:    &tree,
		Cipher:  aes.NewCipher(),
	})
	if err != nil {
		return c.JSON(http.StatusInternalServerError, map[string]interface{}{
			"status_code": http.StatusInternalServerError,
			"message":     err.Error(),
			"data":        nil,
		})
	}

	// Emit the encrypted YAML file
	result, err := store.EmitEncryptedFile(tree)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, map[string]interface{}{
			"status_code": http.StatusInternalServerError,
			"message":     err.Error(),
			"data":        nil,
		})
	}

	return c.JSON(http.StatusOK, model.Response{
		StatusCode: http.StatusOK,
		Message:    "Successful Process",
		Data:       string(result),
	})
}

func (f *FileHandler) HandlerFileDecrypted(c echo.Context) error {
	helper.LoadAWSAccess()
	name := c.FormValue("name")
	log.Debug("filename is : ", name)

	yamlFile, err := c.FormFile("yaml-file")
	if err != nil {
		log.Error(err.Error())
		return c.JSON(http.StatusBadRequest, model.Response{
			StatusCode: http.StatusBadRequest,
			Message:    err.Error(),
			Data:       nil,
		})
	}

	src, err := yamlFile.Open()
	if err != nil {
		log.Error(err.Error())
		return c.JSON(http.StatusInternalServerError, model.Response{
			StatusCode: http.StatusInternalServerError,
			Message:    err.Error(),
			Data:       nil,
		})
	}
	defer src.Close()

	// Read the file content
	fileContent, err := io.ReadAll(src)
	if err != nil {
		log.Error(err.Error())
		return c.JSON(http.StatusInternalServerError, model.Response{
			StatusCode: http.StatusInternalServerError,
			Message:    err.Error(),
			Data:       "Failed to read file content",
		})
	}
	log.Debug("Original content: " + string(fileContent))

	// Decrypt the file using sops
	plaintextData, err := decrypt.Data(fileContent, "yaml")
	if err != nil {
		log.Error("Failed to decrypt data: %v", err)
		return c.JSON(http.StatusInternalServerError, model.Response{
			StatusCode: http.StatusInternalServerError,
			Message:    err.Error(),
			Data:       "Decrypt content failed",
		})
	}

	// Print the decrypted content
	log.Debug("Print the decrypted content: " + string(plaintextData))

	return c.JSON(http.StatusOK, model.Response{
		StatusCode: http.StatusOK,
		Message:    "Successful Process",
		Data:       string(plaintextData),
	})
}
