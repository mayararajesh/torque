<?php

/**
 * This is a modified version of "jQuery File Tree" Plugin and "cfilebrowser" Yii Extension
 * Functionalities are modified to suit CCM requirements
 * 
 * @author Santosh Kumar Gupta <santosh0705@gmail.com>
 *
 */

/**
 * Display a file browser element on the page
 *
 * This widget uses the jquery plugin 'jQuery File Tree' that can
 * be found at: @see http://abeautifulsite.net/blog/2008/03/jquery-file-tree/.
 * To keep up to date with the plugin. Please visit the project page on
 * Google Code: http://code.google.com/p/cfilebrowser
 *
 * @author		Kevin Bradwick <kbradwick@gmail.com>
 * @version		1.0
 * @url			http://code.google.com/p/cfilebrowser
 * @licence		MIT - http://www.opensource.org/licenses/mit-license.php
 *
 */


/**
 * Copyright (c) 2010 Kevin Bradwick <kbradwick@gmail.com>
 *	
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *	
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *	
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class CFileBrowserWidget extends CWidget
{
	/**
	 * The ID of the div element
	 *
	 * @access	public
	 * @var		string
	 */
	public $containerID = 'filebrowser';
	
	
	/**
	 * Specify the file root
	 * 
	 * @access	public
	 * @var 	string
	 */
	public $root = '/';
	
	
	/**
	 * The url of the script that acts as the connector.
	 * 
	 * @access	public
	 * @var 	string
	 */
	public $script = '';
	
	
	/**
	 * Event to trigger folder/collapse
	 * 
	 * @access	public
	 * @var 	string
	 */
	public $folderEvent = 'click';
	
	
	/**
	 * Folder expand speed. Default 500ms (-1 for no animation)
	 * 
	 * @access	public
	 * @var		integer
	 */
	public $expandSpeed = 500;
	
	
	/**
	 * The collapse speed. Default 500ms (-1 for no animation)
	 * 
	 * @access	public
	 * @var		integer
	 */
	public $collapseSpeed = 500;
	
	
	/**
	 * The easing function (optional)
	 * 
	 * @access	public
	 * @var		string
	 */
	public $expandEasing = '';
	
	
	/**
	 * The collapse easing function (optional)
	 * 
	 * @access	public
	 * @var		string
	 */
	public $collapseEasing = '';
	
	
	/**
	 * Limit browsing to one folder at a time
	 * 
	 * @access 	private
	 * @var		boolean
	 */
	public $multiFolder = false;
	
	
	/**
	 * Loading message
	 * 
	 * @access	public
	 * @var		string
	 */
	public $loadMessage = 'Loading File Browser';
	
	
	/**
	 * Specify your custom CSS file (set false for nothing)
	 * 
	 * @access	public
	 * @var		mixed
	 */
	public $cssFile = null;
	
	
	/**
	 * Callback function of a selected file
	 *
	 * @access	public
	 * @var		string
	 */
	public $filecallbackFunction = '';
	
	/**
	 * Callback function of a selected dir
	 *
	 * @access	public
	 * @var		string
	 */
	public $dircallbackFunction = '';
	
	
	/**
	 * The init method
	 * 
	 * @access 	public
	 * @return	null
	 */
	public function init()
	{
		if(empty($this->script))
			throw new CException('Please specify the script url to the plugins connector');
		#if (!Yii::app()->user->checkAccess('authenticated'))
		#		throw new CHttpException(403, 'You are not authorized to perform this action');
		if(!is_dir($this->root))
			$this->root = '/';
		$this->_loadScripts();
		$this->_loadStyles();
		parent::init();
	}
	
	/**
	 * Run
	 * 
	 * This is the main function that gets called
	 * to render stuff by the widget
	 * 
	 * @access	public
	 * @return	null
	 */
	public function run()
	{
		$options=array(
				'script'=>$this->script,
				'root'=>$this->root,
				'folderEvent'=>$this->folderEvent,
				'expandSpeed'=>$this->expandSpeed,
				'collapseSpeed'=>$this->collapseSpeed,
				'multiFolder'=>$this->multiFolder,
				'loadMessage'=>$this->loadMessage,
				);
		if (!empty($this->expandEasing)) $options['expandEasing']=$this->expandEasing;
		if (!empty($this->collapseEasing)) $options['collapseEasing']=$this->collapseEasing;
		$encodedopt=CJavaScript::encode($options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->containerID,"jQuery('#{$this->containerID}').fileTree($encodedopt,$this->filecallbackFunction,$this->dircallbackFunction);");
		$this->render('filebrowser',
			array(
				  'script'=>$this->script
			)
		);
		
	}
	
	/**
	 * Load scripts
	 * 
	 * @access	private
	 * @return	null
	 */
	private function _loadScripts()
	{
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		
		$basePath = Yii::getPathOfAlias('application.extensions.cfilebrowser.assets');
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath);
		
		$cs->registerScriptFile($baseUrl.'/jquery.easing.js');
		$cs->registerScriptFile($baseUrl.'/jqueryFileTree.js');
	}
	
	/**
	 * Load styles
	 * 
	 * @access	private
	 * @return null
	 */
	private function _loadStyles()
	{
		if($this->cssFile === false)
			return false;
		$cs=Yii::app()->getClientScript();
		$basePath = Yii::getPathOfAlias('application.extensions.cfilebrowser.assets');
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath);
		if(is_null($this->cssFile))
			$cs->registerCssFile($baseUrl.'/jqueryFileTree.css');
		else
			$cs->registerCssFile($this->cssFile);
	}
}
?>