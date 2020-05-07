<?php


namespace Qcth\Wechat\Plug;


use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 微信素材管理
 * Class Material
 * @package qcth\laravel_open\plug
 */
class WechatMaterial extends Common {

    use TokenTrait,CurlTrait;


	/**
	 * 上传素材
     * 说明： 如果是 /1.jpg开头的，调用接口时，需转成 ./1.jpg 形式
	 * @param     $type
	 * @param     $file_path
	 * @param int $mediaType 1 临时 0 永久
	 * @param  array  素材描述数组　例：　array('title'=>'标题','desc'=>'描述')
	 * @return mixed
	 */
	public function upload($type,$file_path,$file_name='',$description=NULL,$mediaType = 0) {

		if ( $mediaType ) {
			//临时素材
			$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$this->authorizer_access_token()}&type=$type";
		} else {
			//永久素材
			$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$this->authorizer_access_token()}&type=$type";
		}

        //上传到微信后的文件名
		if(empty($file_name)){
            $file_name=basename($file_path);
        }else{
            $file_name=$file_name.strrchr($file_path,'.'); //文件名加后缀
        }

		if ( class_exists( '\CURLFile' ) ) {
			//关键是判断curlfile,官网推荐php5.5或更高的版本使用curlfile来实例文件
			$filedata = [
				'media' => new \CURLFile ( realpath( $file_path ),'',$file_name)
			];


		} else {
			$filedata = [
				'media' => '@' . realpath( $file_path )
			];

		}

		//描述信息
        if($description){
            $filedata['description']=json_encode($description,JSON_UNESCAPED_UNICODE);
        }


		$result = $this->curl( $url, $filedata);
		
		return json_decode($result,true);
	}

	/**
	 * 下载素材
	 *
	 * @param $media_id
	 * @param $file
	 *
	 * @return int
	 */
	public function download( $media_id, $file ) {
		$url    = "https://api.weixin.qq.com/cgi-bin/media/get?access_token={$this->authorizer_access_token()}&media_id=$media_id";
		$result = $this->curl( $url );
		$dir    = dirname( $file );
		is_dir( $dir ) || mkdir( $dir, 0755, true );

		return file_put_contents( $file, $result );
	}

	//获取永久素材
	public function getMaterial( $media_id ) {
		$url  = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token={$this->authorizer_access_token()}";
		$json = '{"media_id":"' . $media_id . '"}';

		$result = $this->curl( $url, $json );

		return json_decode($result,true);
	}

	//删除永久素材
	public function delete( $media_id ) {
		$url     = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token={$this->authorizer_access_token()}";
		$json    = '{"media_id":"' . $media_id . '"}';
		$content = $this->curl( $url, $json );

        return json_decode($content,true);
	}

	//新增永久图文素材
	public function addNews( $articles ) {
		$url     = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token={$this->authorizer_access_token()}";
		$content = $this->curl( $url, json_encode($articles ,JSON_UNESCAPED_UNICODE));

        return json_decode($content,true);
	}

	//修改永久图文素材
	public function editNews( $article ) {
		$url     =  "https://api.weixin.qq.com/cgi-bin/material/update_news?access_token={$this->authorizer_access_token()}";
		$content = $this->curl( $url, json_encode(  $article ,JSON_UNESCAPED_UNICODE) ) ;
        return json_decode($content,true);
	}

	//获取素材总数
	public function total() {
		$url     =  "https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token={$this->authorizer_access_token()}";
		$content = $this->curl( $url );

        return json_decode($content,true);
	}

	//获取素材列表
	public function lists( $param ) {
		$url     =  "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token={$this->authorizer_access_token()}";
		$content = $this->curl( $url, json_encode( $param, JSON_UNESCAPED_UNICODE ) );

        return json_decode($content,true);
	}
	
	//上传图文消息内的图片获取URL
	//本接口所上传的图片不占用公众号的素材库中图片数量的5000个的限制。图片仅支持jpg/png格式，大小必须在1MB以下。
	public function addNewsImage($file_path){
		$url     = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token={$this->authorizer_access_token()}";
		$file_path = realpath( $file_path );
		if ( class_exists( '\CURLFile' ) ) {
			//关键是判断curlfile,官网推荐php5.5或更高的版本使用curlfile来实例文件
			$filedata = [
				'media' => new \CURLFile ( realpath( $file_path ) )
			];
		} else {
			$filedata = [
				'media' => '@' . realpath( $file_path )
			];
		}
		$result = $this->curl( $url, $filedata);

		 return json_decode($result,true);
	}
}