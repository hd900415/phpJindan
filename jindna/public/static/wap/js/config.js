var config = {
	commonUrl: 'http://118.31.21.30',
	request:  function(url, data, callback) {
		$.ajax({
		  url: 'http://127.0.0.1'+url, // 请求的url
		  method: 'POST',
		  data: data,
		  dataType:"json",
		  success: function(data) {
		  	// data=JSON.parse(data)
		  	if(data.code === 1) {
			  	if(callback) {
			  		callback(data);
			  	}
		  	} else {
		  		// layer.msg(data.msg,{time:1000},function(){
		  		// 	location.href = 'index.html';
		  		// })
		  		return
		  	}
		  },
		  complete: function() {
		  	// console.log('完成')
		  }
		})	
	},
	setStorage: function(key, value) {
		localStorage[key] = value
	},
	getStorage: function(key) {
		return localStorage[key]
	}
}