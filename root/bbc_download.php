<?php
/*
		filename:	bbc_download.php
		  Author:	CyberAlien : http://www.stsoftware.biz

		   Notes:	CyberAlien (aka CA ;) ) released the enhanced bbcode parser to the public
					on the 31 May 2005 @ 7:38 am (for more information see the following post
					http://www.phpbbstyles.com/viewtopic.php?t=6107 )

		 Version:	1.0.7
       
	 Description:	Used with the XS Syntax Highlighter to download the contents of a syntax block
*/

define('IN_PHPBB', true);
define('IN_XSBBC_DOWNLOAD', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.'.$phpEx);

$post_id = request_var('p', 0);
$code_id = request_var('item', 0);
$mode = request_var('mode', '');

if( !$post_id )
{
	trigger_error('XS_SH_NO_TOPIC_ID');
}

// Extract Post or PM from DB $mode sets information to get
$sql = "SELECT * FROM " . ( $mode == 'pm' ? PRIVMSGS_TABLE : POSTS_TABLE ) . "
				WHERE " . ( $mode == 'pm' ? 'msg_id' : 'post_id' ) . "={$post_id}";
if ( !($result = $db->sql_query($sql)) )
{
	trigger_error('XS_SH_NO_TOPIC');
}
if (($posttext = $db->sql_fetchrow($result)) === false)
{
	trigger_error('XS_SH_NO_TOPIC');
}
$db->sql_freeresult($result);

// Define some variables for later use
$code_filename = '';
$code_text = '';
define('EXTRACT_CODE', $code_id);

// Compile the Post / PM
$bbcode_uid = $posttext['bbcode_uid'];
$sh_bbcode->allow_bbcode = true;
$sh_bbcode->allow_smilies = $config['allow_smilies'] && $posttext['enable_smilies'] ? true : false;

$sh_bbcode->code_post_id = ($mode == 'pm' ? $posttext['msg_id'] : $posttext['post_id']);
$message = $sh_bbcode->parse(($mode == 'pm' ? $posttext['message_text'] : $posttext['post_text']), $bbcode_uid);
$sh_bbcode->code_post_id = 0;

if(!strlen($sh_bbcode->code_text))
{
	trigger_error('XS_SH_NO_CONTENT');
}

$code_text = undo_htmlspecialchars($sh_bbcode->code_text, true);

if(empty($sh_bbcode->code_filename))
{
	$code_filename = 'code_' . $post_id . ($code_id ? '_' . $code_id : '') . '.txt';
}
else
{
	$code_filename = $sh_bbcode->code_filename;
}

// Send the Data to the user for download
header('Content-Type: application/force-download');
header('Content-Length: ' . strlen($code_text));
header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Disposition: attachment; filename="' . $code_filename . '"');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

echo $code_text;

?>