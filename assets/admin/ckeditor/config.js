/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.removePlugins = 'iframe, find, div,a11yhelp,about,bidi,menu,contextmenu,dialogadvtab,div,elementspath,enterkey,entities,find,fakeobjects,flash,floatingspace,forms,horizontalrule,htmlwriter,iframe, link, magicline,newpage,pagebreak,print, save,menubutton,scayt,selectall,showblocks,showborders,smiley,sourcearea,specialchar,stylescombo,tab,table, tabletools,templates,undo, language';
	config.pasteFilter = 'h1 h2 p ul ol li; img[!src, alt]; a[!href]';
	config.forcePasteAsPlainText = true;

	config.pasteFromWordRemoveFontStyles = true;
	config.pasteFromWordRemoveStyles = true;
	config.pasteFromWordNumberedHeadingToList = true;
	config.filebrowserImageBrowseUrl = 'http://localhost/ims/assets/admin/kcfinder/browse.php?opener=ckeditor&type=images';
	config.filebrowserFlashBrowseUrl = 'http://localhost/ims/assets/admin/kcfinder/browse.php?opener=ckeditor&type=flash';
	config.filebrowserUploadUrl = 'http://localhost/ims/assets/admin/kcfinder/upload.php?opener=ckeditor&type=files';
	config.filebrowserImageUploadUrl = 'http://localhost/ims/assets/admin/kcfinder/upload.php?opener=ckeditor&type=images';
	config.filebrowserFlashUploadUrl = 'http://localhost/ims/assets/admin/kcfinder/upload.php?opener=ckeditor&type=flash';
};