package helper

import (
	"context"
	"github.com/aws/aws-sdk-go-v2/aws"
	"github.com/aws/aws-sdk-go-v2/config"
	"github.com/aws/aws-sdk-go-v2/service/sts"
	"os"
	"sops-guardians/log"
)

func LoadAWSAccess() error {
	// Check if AWS environment variables are set
	awsAccessKeyID := os.Getenv("AWS_ACCESS_KEY_ID")
	awsSecretAccessKey := os.Getenv("AWS_SECRET_ACCESS_KEY")

	var cfg aws.Config
	var err error

	if awsAccessKeyID == "" || awsSecretAccessKey == "" {
		log.Debug("Using AWS PROFILE to authenticate with AWS")
		// Retrieve AWS profile from environment variable or set a default
		awsProfile := os.Getenv("AWS_PROFILE")
		if awsProfile == "" {
			awsProfile = "thangta" // Set your desired default profile here
		}

		// Load the AWS configuration with the specified profile
		cfg, err = config.LoadDefaultConfig(context.TODO(),
			config.WithRegion("us-west-2"),
			config.WithSharedConfigProfile(awsProfile),
		)
		if err != nil {
			log.Errorf("Failed to load AWS configuration:", err)
			return err
		}
	} else {
		log.Debug("Using AWS Environment to authenticate with AWS")
		// Load the AWS configuration using environment variables
		cfg, err = config.LoadDefaultConfig(context.TODO(),
			config.WithRegion("us-west-2"),
		)
		if err != nil {
			log.Error("Failed to load AWS configuration:", err)
			return err
		}
	}

	// Create an STS client
	stsClient := sts.NewFromConfig(cfg)

	// Call GetCallerIdentity to validate AWS credentials
	callerIdentity, err := stsClient.GetCallerIdentity(context.TODO(), &sts.GetCallerIdentityInput{})
	if err != nil {
		log.Error("Failed to get caller identity:", err)
		return err
	}

	// Print the caller identity details
	log.Debugf("Caller Identity: Account - %s, ARN - %s, UserID - %s\n",
		*callerIdentity.Account,
		*callerIdentity.Arn,
		*callerIdentity.UserId)
	return nil
}
