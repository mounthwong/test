<?php
namespace Manager\Controller;
use Think\Controller;

/** 
* 文件上传
*  
* @author         gm 
* @since          1.0 
*/  
class UploadController extends Controller {
    public function index(){ 
   		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize = 3145728;
		//$upload->rootPath =  C("CONST_UPLOADS");
		$upload->rootPath =  C("CONST_UPLOADS_EXAM");
		$upload->savePath = '';
		$upload->saveName = array('uniqid','');
		$upload->exts     = array('xls', 'xlsx','jpg','mp3','wav');
		$upload->autoSub  = true;
		$upload->subName  = array('date','Ym');	     
	    $info = $upload->upload();
	    //var_dump($info);exit();
	    if(!$info) {// 上传错误提示错误信息
	    	$arr_return["issuc"] = 0;
			$arr_return["msg"] = $upload->getError();
	       	$this->ajaxReturn($arr_return);
	    }else{// 上传成功
	    	$arr_return["issuc"] = 1;
			//$arr_return["msg"] = $info["file"];
			$arr_return["msg"] = $info["Filedata"];
	       	$this->ajaxReturn($arr_return);
	    }
	}
	
	
	public function indexexam(){ 
   		$upload = new \Think\Upload();// 实例化上传类
		//$upload->maxSize = 3145728;
		$upload->rootPath =  C("CONST_UPLOADS_EXAM");
		$upload->savePath = '';
		$upload->saveName = array('uniqid','');
		$upload->exts     = array('xls', 'xlsx','jpg','mp3','wav');
		$upload->autoSub  = true;
		$upload->subName  = array('date','Ym');	     
	    $info = $upload->upload();
	    //var_dump($info);exit();
	    if(!$info) {// 上传错误提示错误信息
	    	$arr_return["issuc"] = 0;
			$arr_return["msg"] = $upload->getError();
	       	$this->ajaxReturn($arr_return);
	    }else{// 上传成功
	    	$arr_return["issuc"] = 1;
			$arr_return["msg"] = $info["file"];
			//$arr_return["msg"] = $info["Filedata"];
	       	$this->ajaxReturn($arr_return);
	    }
	}

	public function bookmp3(){ 
   		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize = 3145728;
		//$upload->rootPath =  C("CONST_UPLOADS_BOOK");
		$upload->rootPath =  C("CONST_UPLOADS_BOOK");
		$upload->savePath = '';
		$upload->saveName = array('uniqid','');
		$upload->exts     = array('xls', 'xlsx','jpg','mp3','wav');
		$upload->autoSub  = true;
		$upload->subName  = "";	     
	    $info = $upload->upload();
	    //var_dump($info);exit();
	    if(!$info) {// 上传错误提示错误信息
	    	$arr_return["issuc"] = 0;
			$arr_return["msg"] = $upload->getError();
	       	$this->ajaxReturn($arr_return);
	    }else{// 上传成功
	    	$arr_return["issuc"] = 1;
			//$arr_return["msg"] = $info["file"];
			$arr_return["msg"] = $info["file_upload"];
	       	$this->ajaxReturn($arr_return);
	    }
	}
	

	public function uploadfile(){
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");


		// Support CORS
		// header("Access-Control-Allow-Origin: *");
		// other CORS headers if any...
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		    exit; // finish preflight CORS requests here
		}


		if ( !empty($_REQUEST[ 'debug' ]) ) {
		    $random = rand(0, intval($_REQUEST[ 'debug' ]) );
		    if ( $random === 0 ) {
		        header("HTTP/1.0 500 Internal Server Error");
		        exit;
		    }
		}

		// header("HTTP/1.0 500 Internal Server Error");
		// exit;


		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Uncomment this one to fake upload time
		// usleep(5000);

		// Settings
		// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		$targetDir = 'upload_tmp';
		$uploadDir = 'uploads/book/pic/';

		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds


		// Create target dir
		if (!file_exists($targetDir)) {
		    @mkdir($targetDir);
		}

		// Create target dir
		if (!file_exists($uploadDir)) {
		    @mkdir($uploadDir);
		}

		// Get a file name
		if (isset($_REQUEST["name"])) {
		    $fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
		    $fileName = $_FILES["file"]["name"];
		} else {
		    $fileName = uniqid("file_");
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		$uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;


		// Remove old temp files
		if ($cleanupTargetDir) {
		    if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
		    }

		    while (($file = readdir($dir)) !== false) {
		        $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		        // If temp file is current file proceed to the next
		        if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
		            continue;
		        }

		        // Remove temp file if it is older than the max age and is not the current file
		        if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
		            @unlink($tmpfilePath);
		        }
		    }
		    closedir($dir);
		}


		// Open temp file
		if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
		    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}

		if (!empty($_FILES)) {
		    if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		    }

		    // Read binary input stream and append it to temp file
		    if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		    }
		} else {
		    if (!$in = @fopen("php://input", "rb")) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		    }
		}

		while ($buff = fread($in, 4096)) {
		    fwrite($out, $buff);
		}

		@fclose($out);
		@fclose($in);

		rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

		$index = 0;
		$done = true;
		for( $index = 0; $index < $chunks; $index++ ) {
		    if ( !file_exists("{$filePath}_{$index}.part") ) {
		        $done = false;
		        break;
		    }
		}
		if ( $done ) {
		    if (!$out = @fopen($uploadPath, "wb")) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		    }

		    if ( flock($out, LOCK_EX) ) {
		        for( $index = 0; $index < $chunks; $index++ ) {
		            if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
		                break;
		            }

		            while ($buff = fread($in, 4096)) {
		                fwrite($out, $buff);
		            }

		            @fclose($in);
		            @unlink("{$filePath}_{$index}.part");
		        }

		        flock($out, LOCK_UN);
		    }
		    @fclose($out);
		}

	// Return Success JSON-RPC response
	die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}
}

 
