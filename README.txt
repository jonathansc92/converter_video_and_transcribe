Confirgurações

- No Sistema Operacional deve haver os arquivos do FFMPEG e FFPROBE
	Para Windows utilizamos o disco local C, estrutura:
		C:\FFMpeg
		C:\FFMpeg\bin
		C:\FFMpeg\bin\ffmpeg.exe
		C:\FFMpeg\bin\ffprobe.exe

- No arquivo .env, deve se colocar os seguintes atributos, especificando o caminho onde foi salvo os arquivos do FFMPEG:
	FFMPEG_BINARIES=C:\FFMpeg\bin\ffmpeg.exe
	FFPROBE_BINARIES=C:\FFMpeg\bin\ffprobe.exe


Instalar o pacote PMEDIA

Para Laravel 5.4, colocamos no arquivo composer.json 
	"require": {
		"pbmedia/laravel-ffmpeg": "^1.3",
    	},

Documentação https://github.com/pascalbaljetmedia/laravel-ffmpeg