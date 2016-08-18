<?php
namespace BaseBundle\Services;

class DefaultData
{
	// todos los datos
	private $defaultData = array();

	// variables del header del sitio web
	private $htmlHeaderTitle 		= 'Bienvenido';
	private $htmlHeaderDescription 	= 'Intranet Biosur Ltda.';
	private $htmlHeaderAutor 		= 'www.tuciudad.cl';
	private $htmlHeaderKeywords 	= 'biosur, intranet, sistema';

	// variables del footer del sitio web
	private $htmlFooterCopyright 	= '© 2016 Todos los derechos reservados | Intranet BioSur | Desarrollado por www.tuciudad.cl';

	public function __construct()
	{
		// header del html
		$htmlHeader = array(
			'title'			=> $this->htmlHeaderTitle,
			'description'	=> $this->htmlHeaderDescription,
			'autor'			=> $this->htmlHeaderAutor,
			'keywords'		=> $this->htmlHeaderKeywords
			);

		// footer del html
		$htmlFooter = array(
			'copyright' => $this->htmlFooterCopyright
			);

		// agregar arrays a default data
		$this->defaultData['htmlHeader'] = $htmlHeader;
		$this->defaultData['htmlFooter'] = $htmlFooter;
	}

/**
SETTERS
**/
	// agregar datos del header: ('title', 'description', 'autor', 'keywords') or (array())
	public function setHtmlHeader($title = false, $description = false, $autor = false, $keywords = false)
	{
		if(!is_array($title))
		{
			$this->htmlHeaderTitle 			= ($title)			?$title 		:$this->htmlHeaderTitle;
			$this->htmlHeaderDescription 	= ($description)	?$description 	:$this->htmlHeaderDescription;
			$this->htmlHeaderAutor 			= ($autor)			?$autor 		:$this->htmlHeaderAutor;
			$this->htmlHeaderKeywords 		= ($keywords)		?$keywords 		:$this->htmlHeaderKeywords;
		}else{
			$arrayData = $title;

			$this->htmlHeaderTitle 			= (isset($arrayData['title']))			?$arrayData['title']		:$this->htmlHeaderTitle;
			$this->htmlHeaderDescription 	= (isset($arrayData['description']))	?$arrayData['description']	:$this->htmlHeaderDescription;
			$this->htmlHeaderAutor 			= (isset($arrayData['autor']))			?$arrayData['autor']		:$this->htmlHeaderAutor;
			$this->htmlHeaderKeywords 		= (isset($arrayData['keywords']))		?$arrayData['keywords']		:$this->htmlHeaderKeywords;
		}

		// agregar a default data
		$this->defaultData['htmlHeader']['title'] 			= $this->htmlHeaderTitle;
		$this->defaultData['htmlHeader']['description'] 	= $this->htmlHeaderDescription;
		$this->defaultData['htmlHeader']['autor'] 			= $this->htmlHeaderAutor;
		$this->defaultData['htmlHeader']['keywords'] 		= $this->htmlHeaderKeywords;

	}

	// agregar datos del footer ('copyright') or (array())
	public function setHtmlFooter($copyright = false)
	{
		if(!is_array($copyright))
		{
			$this->htmlFooterCopyright = ($copyright)? $copyright: $this->htmlFooterCopyright;
		}else{
			$arrayData = $copyright;

			$this->htmlFooterCopyright = (isset($arrayData['copyright']))? $arrayData['copyright']: $this->htmlFooterCopyright;
		}

		// agregar a default data
		$this->defaultData['htmlFooter']['copyright'] = $this->htmlFooterCopyright;
	}

/**
GETTERS
**/
	// obtener todos los datos
	public function getAll()
	{
		return $this->defaultData;
	}

	//obtener unicamente el header
	public function getHtmlHeader()
	{
		return $this->defaultData['htmlHeader'];
	}

	// obtener unicamente el footer
	public function getHtmlFooter()
	{
		return $this->defaultData['htmlFooter'];
	}
}