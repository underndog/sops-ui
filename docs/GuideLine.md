## Guideline.

### Install Sops-ui

#### Docker-compose

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

### Encrypt the Secret and Configmap data.

![](https://raw.githubusercontent.com/mrnim94/sops-ui/master/docs/picture/step1-encrypt-secret.png)

You must provide the KMS ARN for data encryption. Then press the Next Button.

![](https://raw.githubusercontent.com/mrnim94/sops-ui/master/docs/picture/step2-encrypt-secret.png)

Upload the file that you want to encrypt and click SEND.

![](https://raw.githubusercontent.com/mrnim94/sops-ui/master/docs/picture/step3-encrypt-secret.png)

SOPS-UI will encrypt the file using KMS-AWS and SOPS, and then display the result for you to copy and save as a new file.

![](https://raw.githubusercontent.com/mrnim94/sops-ui/master/docs/picture/step1-decrypt-secret.png)

### Modify the encrypted file using SOPS and KMS-AWS.

![](https://raw.githubusercontent.com/mrnim94/sops-ui/master/docs/picture/step1-decrypt-file.png)

Click Next end Push the encrypted file.  
![](https://raw.githubusercontent.com/mrnim94/sops-ui/master/docs/picture/step2-decrypt-file.png)

Sops-UI will decrypt SOPS and base64, allowing you to easily understand and modify the keys and values.  
Next, Let's click Encrypt and copy them.

![](https://raw.githubusercontent.com/mrnim94/sops-ui/master/docs/picture/step3-decrypt-file.png)