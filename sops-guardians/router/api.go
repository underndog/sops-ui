package router

import (
	"github.com/labstack/echo/v4"
	"sops-guardians/handler"
)

type API struct {
	Echo        *echo.Echo
	FileHandler handler.FileHandler
}

func (api *API) SetupRouter() {

	api.Echo.GET("/", handler.Welcome)
	api.Echo.POST("/decrypt-file", api.FileHandler.HandlerFileDecrypted)
	api.Echo.POST("/encrypt-file", api.FileHandler.HandlerFileEncrypted)
}
