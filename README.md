## sops-ui

You can easily adjust the k8s secrets that are encrypted by SOPS and KMS(AWS)




## Install Sops-UI

Sops-UI support for both linux/amd64 and linux/arm64 OS/ARCH. You can Sops-ui components directly on MAC\_OS

```plaintext
version: '3.8'

services:
  laravel:
    image: mrnim94/sops-ui:latest
    ports:
      - "8080:8080"
    networks:
      - app-network
    environment:
      - SOPS_GUARDIANS_URL=http://golang:9999

  golang:
    image: mrnim94/sops-guardians:latest
    # ports:
    #   - "9999:9999"
    networks:
      - app-network
    environment:
      - AWS_ACCESS_KEY_ID=XXXXXXXXXXXXX
      - AWS_SECRET_ACCESS_KEY=xxxxXXXXXXXXXXXXXXxxxXXXXxxx

networks:
  app-network:
    driver: bridge
```

You need to provide the `AWS_ACCESS_KEY_ID` and `AWS_SECRET_ACCESS_KEY` to encrypt and decrypt data.


Guideline: [How to install Sops-UI and run it](https://github.com/mrnim94/sops-ui/blob/master/docs/GuideLine.md)

![](https://raw.githubusercontent.com/mrnim94/sops-ui/master/docs/picture/step3-encrypt-secret.png)