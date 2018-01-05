<?php

namespace App;

use App\Monitor\Match\MatchBase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Snapshot
 *
 * @property int $id
 * @property int $monitor_id
 * @property int $is_match 匹配中了就为1，至于发不发邮件通知，那是另一回事
 * @property int $is_error 如果有错误，匹配就肯定是false
 * @property string $error_message
 * @property string $http_status_code
 * @property string $headers
 * @property string $body_content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereBodyContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereHeaders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereHttpStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereIsError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereIsNotice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereMonitorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $time_total
 * @property int $time_dns
 * @property int $time_connection
 * @property int $time_transport
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereTimeConnection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereTimeDns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereTimeTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Snapshot whereTimeTransport($value)
 * @property-read \App\Monitor $monitor
 */
class Snapshot extends Model
{
    //
    public function buildFull(){
        is_null($this->http_status_code) && $this->http_status_code=0;
        is_null($this->headers) && $this->headers="";
        is_null($this->body_content) && $this->body_content="";
        is_null($this->time_total) && $this->time_total=0;
        is_null($this->time_connection) && $this->time_connection=0;
        is_null($this->time_dns) && $this->time_dns=0;
        is_null($this->time_transport) && $this->time_transport=0;

    }
    public function monitor()
    {
        return $this->belongsTo('App\Monitor');
    }

	/**
	 * @return MatchBase
	 * @throws \Exception
	 */
	public function getMatcher(){
		$matchClass = "\\App\\Monitor\\Match\\".Str::ucfirst( Str::camel( $this->monitor->match_type."_match" ) );
		if ( ! class_exists( $matchClass ) ) {
			throw new \Exception( "monitor match_type[{$matchClass}] not found!" );
		}
		return new $matchClass($this);
	}
}