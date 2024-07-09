package main

import (
	"github.com/labstack/echo/v4"
	"os"
	"sops-guardians/handler"
	"sops-guardians/log"
	"sops-guardians/router"
)

func init() {
	os.Setenv("APP_NAME", "SOPS-Guardians")
	logger := log.InitLogger(false)
	// Check if KUBERNETES_SERVICE_HOST is set
	if _, exists := os.LookupEnv("KUBERNETES_SERVICE_HOST"); !exists {
		// If not in Kubernetes, set LOG_LEVEL to DEBUG
		os.Setenv("LOG_LEVEL", "DEBUG")
	}
	logger.SetLevel(log.GetLogLevel("LOG_LEVEL"))
	os.Setenv("TZ", "Asia/Ho_Chi_Minh")
}

func main() {

	fileHandler := handler.FileHandler{}

	e := echo.New()

	api := router.API{
		Echo:        e,
		FileHandler: fileHandler,
	}
	api.SetupRouter()
	e.Logger.Fatal(e.Start(":9999"))
}
