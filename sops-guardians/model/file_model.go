package model

type FileContent struct {
	FileName string                 `json:"file_name,omitempty"`
	Content  map[string]interface{} `json:"content,omitempty"`
}
