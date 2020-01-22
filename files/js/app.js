var app = angular.module('submitExample');

app.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
app.controller('customersCrtl', function ($scope, $http, $timeout,$modal,$filter) {
    $http.get('services/getusers.php').success(function(data){
        $scope.list = data;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 50; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter
        $scope.totalItems = $scope.list.length;
    });

    $scope.setPage = function(pageNo) {

    	$scope.currentPage = pageNo;

    	var currentpage =  parseInt($scope.currentPage);
        var limit = parseInt($scope.entryLimit);
        var startid = parseInt((currentpage*limit)-limit);
        var lastid = currentpage*limit;
        var j;
        var totalit = parseInt($scope.totalItems);
        if ($scope.selectedAll) {
    	      $scope.selectedAll = false;
    	  } else {
    	      $scope.selectedAll = true;
    	  }
        for(i=0;i<totalit;i++)
    	{
        	$scope.list[i].Selected=false;
    	}
        $scope.selectedAll=false;
        /*for(i=startid;i<lastid;i++)
    	{
        	if($scope.list[i].Selected == true)
    		{
        		j=j+1;
    		}

    	}*/


    };
    $scope.showtrack = function(trackid) {
    	tracksno = trackid.sno;
    	document.getElementById("track"+tracksno).style.display="block";
    };
    $scope.hidetrack = function(trackid) {
    	tracksno = trackid.sno;
    	document.getElementById("track"+tracksno).style.display="none";
    };
    $scope.filter = function() {
        $timeout(function() {
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };

    $scope.changeProductStatus = function(product){
    	product.active = (product.active=="1" ? "0" : "1");


    	var usersno = product.sno;
    	$http.get('services/edituserdet.php?active='+product.active+'&enduserid='+usersno).success(function(data){
    		 $http.get('services/getusers.php').success(function(data){
    		        $scope.list = data;
    		        $scope.currentPage = 1; //current page
    		        $scope.entryLimit = 50; //max no of items to display in a page
    		        $scope.filteredItems = $scope.list.length; //Initially for no filter
    		        $scope.totalItems = $scope.list.length;
    		    });
    		    $scope.setPage = function(pageNo) {
    		        $scope.currentPage = pageNo;
    		    };
    		    $scope.filter = function() {
    		        $timeout(function() {
    		            $scope.filteredItems = $scope.filtered.length;
    		        }, 10);
    		    };
    		    $scope.sort_by = function(predicate) {
    		        $scope.predicate = predicate;
    		        $scope.reverse = !$scope.reverse;
    		    };
    	});
    };
    $scope.deleteProduct = function(product){
        var deleteuser = product.active;
        var usersno = product.sno;
        if(confirm("Are you sure to remove this user")){
        	/* get user services */
        	$http.get('services/edituserdet.php?delete=1&enduserid='+usersno).success(function(data){

        		$http.get('services/getusers.php').success(function(data){
        			$scope.list = data;
    		        $scope.currentPage = 1; //current page
    		        $scope.entryLimit = 50; //max no of items to display in a page
    		        $scope.filteredItems = $scope.list.length; //Initially for no filter
    		        $scope.totalItems = $scope.list.length;
    		    });
        		$scope.setPage = function(pageNo) {
        	        $scope.currentPage = pageNo;
        	    };
        	    $scope.filter = function() {
        	        $timeout(function() {
        	            $scope.filteredItems = $scope.filtered.length;
        	        }, 10);
        	    };
        	    $scope.sort_by = function(predicate) {
        	        $scope.predicate = predicate;
        	        $scope.reverse = !$scope.reverse;
        	    };
		    });
        }
    };
    $scope.closepopupedituserr = function()
    {
    	 document.getElementById("edituser_success").style.display="none";
    	$scope.userid="";
    	$scope.username="";
    	$scope.usergroup="";
    	$scope.userdepartment="";
    	$scope.userdesignation="";
    	toggle('edituser_blanketr');
    	toggle('edituser_popUpDivr');
    	toggle('divfirstuserr');
    };
    $scope.open = function (product) {
    		$scope.userid=product.sno;
        	$scope.username=product.name;
        	$scope.usergroup=product.groupid;

        	$scope.userdepartment=product.dept;
        	$scope.userdesignation=product.designation;
        	toggle('edituser_blanketr');
        	toggle('edituser_popUpDivr');
        	toggle('divfirstuserr');


    };
    $scope.editusersubmit=function()
    {
    	var usersno = ($scope.userid);
    	var username = ($scope.username);
    	var usergroup = ($scope.usergroup);

    	var userdepartment = ($scope.userdepartment);
    	var userdesignation = ($scope.userdesignation);
    	$http.get('services/edituserdet.php?edituser=1&enduserid='+usersno+'&name='+username+'&group='+usergroup+'&dept='+userdepartment+'&designation='+userdesignation).success(function(responsedata){
    		 document.getElementById("edituser_success").style.display="block";
    		$http.get('services/getusers.php').success(function(data){
    			$scope.list = data;
		        $scope.currentPage = 1; //current page
		        $scope.entryLimit = 50; //max no of items to display in a page
		        $scope.filteredItems = $scope.list.length; //Initially for no filter
		        $scope.totalItems = $scope.list.length;
		    });
    		$scope.setPage = function(pageNo) {
    	        $scope.currentPage = pageNo;
    	    };
    	    $scope.filter = function() {
    	        $timeout(function() {
    	            $scope.filteredItems = $scope.filtered.length;
    	        }, 10);
    	    };
    	    $scope.sort_by = function(predicate) {
    	        $scope.predicate = predicate;
    	        $scope.reverse = !$scope.reverse;
    	    };
    	});
    };

    $scope.selectall = function()
    {

    var currentpage =  parseInt($scope.currentPage);
    var limit = parseInt($scope.entryLimit);
    var startid = parseInt((currentpage*limit)-limit);
    var lastid = currentpage*limit;

    if ($scope.selectedAll) {
	      $scope.selectedAll = false;
	  } else {
	      $scope.selectedAll = true;
	  }
    for(i=startid;i<lastid;i++)
	{

    	$scope.list[i].Selected = $scope.selectedAll;
	}


    };

	$scope.DeleteUsers = function()
    {

		     angular.forEach($scope.list, function (data) {
		    	if(data.Selected==true)
		    	{
		    	$http.get('services/edituserdet.php?delete=1&enduserid='+data.sno).success(function(data){
		    		$http.get('services/getusers.php').success(function(data){
		    			$scope.list = data;
				        $scope.currentPage = 1; //current page
				        $scope.entryLimit = 50; //max no of items to display in a page
				        $scope.filteredItems = $scope.list.length; //Initially for no filter
				        $scope.totalItems = $scope.list.length;
				    });
		    		$scope.setPage = function(pageNo) {
		    	        $scope.currentPage = pageNo;
		    	    };
		    	    $scope.filter = function() {
		    	        $timeout(function() {
		    	            $scope.filteredItems = $scope.filtered.length;
		    	        }, 10);
		    	    };
		    	    $scope.sort_by = function(predicate) {
		    	        $scope.predicate = predicate;
		    	        $scope.reverse = !$scope.reverse;
		    	    };
		    	});

		    	}


      		});
    };
    $scope.ActiveUsers = function()
    {

		     angular.forEach($scope.list, function (data) {
		    	 if(data.Selected==true)
		    	{
		    	$http.get('services/edituserdet.php?active=1&enduserid='+data.sno).success(function(data){
		    		$http.get('services/getusers.php').success(function(data){
		    			$scope.list = data;
				        $scope.currentPage = 1; //current page
				        $scope.entryLimit = 50; //max no of items to display in a page
				        $scope.filteredItems = $scope.list.length; //Initially for no filter
				        $scope.totalItems = $scope.list.length;
				    });
		    		$scope.setPage = function(pageNo) {
		    	        $scope.currentPage = pageNo;
		    	    };
		    	    $scope.filter = function() {
		    	        $timeout(function() {
		    	            $scope.filteredItems = $scope.filtered.length;
		    	        }, 10);
		    	    };
		    	    $scope.sort_by = function(predicate) {
		    	        $scope.predicate = predicate;
		    	        $scope.reverse = !$scope.reverse;
		    	    };
		    	});

		    	}


      		});
    };
    $scope.releaseUser= function(data, index)
    {
        $("#releaseModal").modal("show");
        $scope.releaseData = data;
        $scope.releaseIndex= index;
    };
    $scope.confirmRelease = function()
    {
        $http.get('services/edituserdet.php?active=2&enduserid='+$scope.releaseData.sno).then(function(data){
            $("#releaseModal").modal("hide");
            $scope.ActiveUsers();
            $scope.list.splice($scope.releaseIndex,1);
        });

    };
    $scope.InactiveUsers = function()
    {

		     angular.forEach($scope.list, function (data) {
		    	 if(data.Selected==true)
		    	{
		    	$http.get('services/edituserdet.php?active=0&enduserid='+data.sno).success(function(data){
		    		$http.get('services/getusers.php').success(function(data){
		    			$scope.list = data;
				        $scope.currentPage = 1; //current page
				        $scope.entryLimit = 50; //max no of items to display in a page
				        $scope.filteredItems = $scope.list.length; //Initially for no filter
				        $scope.totalItems = $scope.list.length;
				    });
		    		$scope.setPage = function(pageNo) {
		    	        $scope.currentPage = pageNo;
		    	    };
		    	    $scope.filter = function() {
		    	        $timeout(function() {
		    	            $scope.filteredItems = $scope.filtered.length;
		    	        }, 10);
		    	    };
		    	    $scope.sort_by = function(predicate) {
		    	        $scope.predicate = predicate;
		    	        $scope.reverse = !$scope.reverse;
		    	    };
		    	});

		    	}


      		});
    };

    $scope.iframeclose = function() {

    	 if((($('#upload_target').contents().find('#profilepicval').val())=='') ||  (($('#upload_target').contents().find('#profilepicval').val())=='0'))
    	 {
    		 mytimeout = $timeout($scope.iframeclose,1000);
    	 }
    	 else
    	 {

    		 toggle('blanket');
    	     toggle("popUpDiv");
    	     document.getElementById("popUpDiv").innerHTML="";
    	     $http.get('services/getusers.php').success(function(data){
	    			$scope.list = data;
			        $scope.currentPage = 1; //current page
			        $scope.entryLimit = 50; //max no of items to display in a page
			        $scope.filteredItems = $scope.list.length; //Initially for no filter
			        $scope.totalItems = $scope.list.length;
			    });
	    		$scope.setPage = function(pageNo) {
	    	        $scope.currentPage = pageNo;
	    	    };
	    	    $scope.filter = function() {
	    	        $timeout(function() {
	    	            $scope.filteredItems = $scope.filtered.length;
	    	        }, 10);
	    	    };
	    	    $scope.sort_by = function(predicate) {
	    	        $scope.predicate = predicate;
	    	        $scope.reverse = !$scope.reverse;
	    	    };
    	 }
    };

    $scope.openimageuploader = function(item)
    {
    	toggle('blanket');
    	toggle("popUpDiv");
    	document.getElementById("popUpDiv").innerHTML="<div id='close' onclick='closepopup()' style='position: absolute;right: -12px;top: -15px;'><img src='images/close.png'></div><iframe src='jquery_upload/upload_crop.php?sourceid="+item.sno+"' name='upload_target' id='upload_target' style='width:600px;height:350px;background-color:#fff;'></iframe>";
    };

});
