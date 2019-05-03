<?php

namespace App\Models\Supplier;

use App\Models\Buyer\OrderItem;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Storage;

class BidItem extends Pivot
{

	const STATUS_DEFAULT=0;
	const STATUS_ACCEPTED=1;
	const STATUS_REJECTED=2;

	public $timestamps=false;

    protected $table = 'bid_order_item';
    protected $appends='total';
    protected $attributes=[
    	'bid_id',
	    'order_item_id',
	    'price',
	    'dropoff_fee',
		'pickup_fee',
		'insurance',
		'deliv_date',
		'return_date',
		'notes',
	    'status',
	    'details'
    ];
	protected $casts=[
		'details'=>'json'
	];

    protected $dates=[ 'deliv_date','return_date'];

    public function bid(){
    	return $this->belongsTo(Bid::class);
    }

    public function orderItem(){
    	return $this->belongsTo(OrderItem::class);
    }

	public function getStatusName(){
		switch ($this->status){
			case BidItem::STATUS_DEFAULT: return 'IN REVIEW';
			case BidItem::STATUS_ACCEPTED: return 'ACCEPTED';
			case BidItem::STATUS_CANCELED: return 'CANCELLED';
			default: return 'UNKNOWN';
		}
	}

	public function getTotalAttribute(){
    	return $this->price+$this->dropoff_fee+$this->pickup_fee+$this->insurance;
	}

	public function getAttachmentsAttribute(){
    	$details = $this->details;
    	if(isset($details['attachments'])){
    		$attachments=$details['attachments'];
	    }else{
    		$attachments=[];
	    }
	    $new_attachments=[];
		$allowedMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
		$storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
		foreach ($attachments as $attachment){
			$file_path=$storagePath.'bids/'.$this->bid->order_id.'/'.$this->bid->supplier_id.'/'.$attachment;
			if(file_exists($file_path)){
				$contentType = mime_content_type($file_path);
				$info = pathinfo($file_path);
				$ext = $info['extension'];
				if(in_array($contentType,$allowedMimeTypes)){
					$thumbnail=route('supplier.bids.attachments.show',[$this->bid_id,$attachment]);
				}elseif(file_exists(public_path('images/icons/'.$ext.'/'.$ext.'-80_32.png'))){
					$thumbnail= asset('images/icons/'.$ext.'/'.$ext.'-80_32.png');
				}else{
					$thumbnail= asset('images/icons/document.png');
				}
			}else{
				$thumbnail=route('supplier.bids.attachments.show',[$this->bid_id,$attachment]);
			}

    		array_push($new_attachments,[
    			'thumbnail'=>$thumbnail,
			    'url'=>route('supplier.bids.attachments.show',[$this->bid_id,$attachment]),
			    'server_name'=>$attachment,
			    'name'=>substr($attachment,11)
		        ]);
	    }
	    return $new_attachments;
	}
}
