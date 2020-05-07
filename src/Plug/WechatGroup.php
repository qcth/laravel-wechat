<?php

namespace Qcth\Wechat\Plug;


use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 会员组管理
 * Class Group
 * @package qcth\laravel_open\plug
 */
class WechatGroup extends Common {
    use TokenTrait,CurlTrait;


	//查询所有分组
	public function getAllGroups() {
		$url     = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token={$this->authorizer_access_token()}";
		$content = $this->curl( $url );

		return json_decode($content,true);
	}

	
	//创建分组
	public function create( $group ) {
		$url     = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token={$this->authorizer_access_token()}";
		$content = $this->curl( $url, urldecode( json_encode($group) ) );

        return json_decode($content,true);
	}

	//查询用户所在分组
	public function getUserGroup( $openid ) {
		$url     = "https://api.weixin.qq.com/cgi-bin/groups/getid?access_token={$this->authorizer_access_token()}";
		$user    = '{"openid": ' . $openid . '}';
		$content = $this->curl( $url, $user );

        return json_decode($content,true);
	}

	//修改分组名
	public function changeGroupName( $group ) {
		$url     = "https://api.weixin.qq.com/cgi-bin/tags/update?access_token={$this->authorizer_access_token()}";
		$content = $this->curl( $url, urldecode( json_encode(  $group  ) ) );

        return json_decode($content,true);
	}

	//移动用户分组
	public function changUserGroup( $param ) {
		$url     = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token={$this->authorizer_access_token()}";
		$content = $this->curl( $url, urldecode( json_encode( $this->urlencodeArray( $param ) ) ) );

        return json_decode($content,true);
	}

	//批量移动用户分组
	public function moveUserToGroup( $param ) {
		$url     = "https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate?access_token={$this->authorizer_access_token()}";
		$content = $this->curl( $url, urldecode( json_encode( $this->urlencodeArray( $param ) ) ) );

        return json_decode($content,true);
	}

	
	
	//删除分组
	public function delGroup( $param ) {
		$url     =  "https://api.weixin.qq.com/cgi-bin/tags/delete?access_token={$this->authorizer_access_token()}";
		$content = $this->curl( $url, urldecode(json_encode($param)));

        return json_decode($content,true);
	}
	
	
}