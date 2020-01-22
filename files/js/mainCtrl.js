var app = angular.module('submitExample', ['ui.bootstrap']);
//var base_url1 = "http://localhost/endetect/";
app.directive('modalDialog', function() {
	  return {
	    restrict: 'E',
	    scope: {
	      show: '='
	    },
	    replace: true, // Replace with the template below
	    transclude: true, // we want to insert custom content inside the directive
	    link: function(scope, element, attrs) {
	      scope.dialogStyle = {};
	      if (attrs.width)
	        scope.dialogStyle.width = attrs.width;
	      if (attrs.height)
	        scope.dialogStyle.height = attrs.height;
	      scope.hideModal = function() {
	        scope.show = false;
	      };
	    },
	    template: "<div class='ng-modal' ng-show='show'><div class='ng-modal-overlay' ng-click='hideModal()'></div><div class='ng-modal-dialog' ng-style='dialogStyle'><div class='ng-modal-close' ng-click='hideModal()'>X</div> <div class='ng-modal-dialog-content' ng-transclude></div> </div> </div>" // See below
	  };
	});
app.directive('numbersOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9]/g, '');

                    if (transformedInput !== text) {
                        ngModelCtrl.$setViewValue(transformedInput);
                        ngModelCtrl.$render();
                    }
                    return transformedInput;
                }
                return undefined;
            }
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});
app.controller('resetPasswordCtrl', function ($scope, $http) {
          $scope.resetpassword = function()
          {

          };
      });
app.controller('PaymentHistoryController', function ($scope, $http) {
	$scope.discount="";
	$scope.amountafterdiscount="";
	var data = $.param({
    });
    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;',
            'X-API-KEY': 'A1F584C3083132CE18DCDA579C753579D3276AAB'
        }
    }
	$http.post("api/ed/getPaymentHistory",data,config).then(function(response)
	{
		if(response.data=="0" || response.data==0)
		{
			$scope.listPaymentHistory=0;
		}
		else
		{
			$scope.listPaymentHistory=1;
		}
		$scope.ng_payment_success = response.data;
	});

	$scope.redeempromo = function(transDet)
	{
		var promocode = $scope.promocode;
        var packageid = transDet.packageid;
        var licenseno = transDet.total_lic;
        var mobile = $("#pricing_mobile").val();
        var pincode = $("#user_pincode").val();
        var data = $.param({
            promocode: promocode,
            packageid: packageid,
            licenseno: licenseno,
            mobileno: transDet.billing_mobile,
            pincode: transDet.billing_zip
        });
        var config = {
            headers : {
	            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;',
	            'X-API-KEY': 'A1F584C3083132CE18DCDA579C753579D3276AAB'
	        }
        };
        $http.post("api/ed/promocode/",data,config).then(function(response)
        {
        	if(response.data.promostatus=="1")
            {

                $scope.subtotal = (response.data.baseprice).toFixed(2);
                $scope.discount = parseFloat(response.data.promodiscount).toFixed(2);
                $scope.amountafterdiscount = (response.data.totalamount).toFixed(2);
                 $scope.totalamount = (response.data.totalaftergst).toFixed(2);
                $scope.gstPer = parseFloat(response.data.gstamount).toFixed(2);
            }
            else
            {

                $scope.subtotal = (response.data.baseprice).toFixed(2);
                $scope.discount = parseFloat(response.data.promodiscount).toFixed(2);
                $scope.amountafterdiscount = (response.data.totalamount).toFixed(2);
                $scope.totalamount = (response.data.totalaftergst).toFixed(2);
				$scope.gstPer = parseFloat(response.data.gstamount).toFixed(2);;
            }
        });
	};
	$scope.licenseRenew = function(value)
	{
		var month;
		var data = $.param({
			packageid: value.packageid
    	});
		$http.post("api/ed/getLicenseDet",data,config).then(function(response)
		{
			$scope.licenseDet = response.data;
			$scope.transDet = value;
			if(response.data.packagedurationtype=="year")
			{
				month = 12 * (parseFloat(response.data.packageduration).toFixed(2));
			}
			else if(response.data.packagedurationtype=="month")
			{
				month = (parseFloat(response.data.packageduration).toFixed(2));
			}
			else
			{
				month=1;
			}
			$scope.subtotal = (parseFloat(value.total_lic).toFixed(2)) * (parseFloat(response.data.amount).toFixed(2)) * (parseFloat(month).toFixed(2));
			$scope.gstPer = ((($scope.subtotal) * (response.data.igst))/100);
			$scope.totalamount = $scope.subtotal + $scope.gstPer;
			$("#licensePackage").addClass("hide");
			$("#packageFG").removeClass("hide");
		});
	};
	$scope.renewPackage = function(transDet)
	{
		var promocode = $scope.promocode;
		var totallicenses = transDet.total_lic;
		var packageid = $scope.licenseDet.packageid;
		var data = $.param({
            promocode: btoa(promocode),
            packageid: packageid,
            totallicenses: totallicenses,
            lickey: btoa(transDet.lickey)
        });
        var config = {
        headers : {
	            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;',
	            'X-API-KEY': 'A1F584C3083132CE18DCDA579C753579D3276AAB'
	        }
	    }
	    $http.post("api/ed/getOwnerTrasactionDet",data,config).then(function(response)
		{
				var options = {
                    "key": atob(response.data.razorpayapikey),
                    "amount": atob(response.data.amount), // 2000 paise = INR 20
                    "subscription_id":atob(response.data.order_id),
                    "name": response.data.companyname,
                    "description": response.data.companydesc,
                    "image": response.data.logoimage,
                    "handler": function (response2) {
                        if(typeof response2.razorpay_payment_id == 'undefined' ||  response2.razorpay_payment_id < 1) {
                        	$("#errorModal").modal("show");
                            document.getElementById.innerHTML="Transaction failed";
                              var form = $('<form action="'+response.data.callbackurl+'" method="post">' +
                              '<input type="hidden" name="res_shopping_order_id" value="' + response.data.order_id + '" />' +
                              '<input type="hidden" name="res_razorpay_payment_id" value="' + response2.razorpay_payment_id + '" />' +
                              '<input type="hidden" name="PAY_TYPE" value="'+ response.data.transactiontype +'" />' +
                              '<input type="hidden" name="res_razorpay_order_id" value="' + response2.razorpay_order_id + '" />' +
                              '<input type="hidden" name="res_razorpay_signature" value="Razorpay" />' +
                              '<input type="hidden" name="res_response" value="failed" />' +
                              '<input type="hidden" name="lickey" value="'+transDet.lickey+'" />' +
                              '<input type="hidden" name="packageid" value="'+transDet.packageid+'" />' +
                              '<input type="hidden" name="transactionid" value="'+response.data.transactionid+'" />' +
                              '</form>');
                            $('body').append(form);
                            form.submit();
                        }
                        else
                        {
                            var paymentid = response2.razorpay_payment_id;
                            var gh = paymentid.split("_");
                            if(gh["0"]=="pay")
                            {
                            	$("#errorModal").modal("show");
                            	document.getElementById.innerHTML="Transaction successfully updated";
                                var form = $('<form action="'+response.data.callbackurl+'" method="post">' +
                                  '<input type="hidden" name="res_shopping_order_id" value="' + response.data.order_id + '" />' +
                                  '<input type="hidden" name="res_razorpay_payment_id" value="' + response2.razorpay_payment_id + '" />' +
                                  '<input type="hidden" name="PAY_TYPE" value="'+ response.data.transactiontype +'" />' +
                                  '<input type="hidden" name="res_razorpay_order_id" value="' + response2.razorpay_order_id + '" />' +
                                  '<input type="hidden" name="res_razorpay_signature" value="Razorpay" />' +
                                  '<input type="hidden" name="res_response" value="success" />' +
                                  '<input type="hidden" name="lickey" value="'+transDet.lickey+'" />' +
                                  '<input type="hidden" name="packageid" value="'+transDet.packageid+'" />' +
                                  '<input type="hidden" name="transactionid" value="'+response.data.transactionid+'" />' +
                                  '</form>');
                                $('body').append(form);
                                form.submit();
                            }
                            else
                            {
                            	$("#errorModal").modal("show");
                            	document.getElementById.innerHTML="Transaction abort";
                                var form = $('<form action="'+response.data.callbackurl+'" method="post">' +
                                  '<input type="hidden" name="res_shopping_order_id" value="' + response.data.order_id + '" />' +
                                  '<input type="hidden" name="res_razorpay_payment_id" value="' + response2.razorpay_payment_id + '" />' +
                                  '<input type="hidden" name="PAY_TYPE" value="'+ response.data.transactiontype +'" />' +
                                  '<input type="hidden" name="res_razorpay_order_id" value="' + response2.razorpay_order_id + '" />' +
                                  '<input type="hidden" name="res_razorpay_signature" value="Razorpay" />' +
                                  '<input type="hidden" name="res_response" value="abort" />' +
                                  '<input type="hidden" name="lickey" value="'+transDet.lickey+'" />' +
                                  '<input type="hidden" name="packageid" value="'+transDet.packageid+'" />' +
                                  '<input type="hidden" name="transactionid" value="'+response.data.transactionid+'" />' +
                                  '</form>');
                                $('body').append(form);
                                form.submit();
                            }
                        }
                    },
                    "modal": {
                        "ondismiss": function(){

                        	/*console.log("Owner Dismiss transaction");
                            var form = $('<form action="'+response.data.callbackurl+'" method="post">' +
                              '<input type="hidden" name="res_shopping_order_id" value="' + response.data.order_id + '" />' +
                              '<input type="hidden" name="PAY_TYPE" value="'+ response.data.transactiontype +'" />' +
                              '<input type="hidden" name="res_razorpay_order_id" value="' + response2.razorpay_order_id + '" />' +
                              '<input type="hidden" name="res_razorpay_signature" value="Razorpay" />' +
                              '<input type="hidden" name="res_response" value="dismiss" />' +
                               '<input type="hidden" name="lickey" value="'+transDet.lickey+'" />' +
                               '<input type="hidden" name="packageid" value="'+transDet.packageid+'" />' +
                               '<input type="hidden" name="transactionid" value="'+response.data.transactionid+'" />' +
                              '</form>');
                            $('body').append(form);
                            form.submit();*/
                        }
                    },
                    "prefill": {
                        "name": atob(response.data.name),
                        "email": atob(response.data.email),
                        "contact": atob(response.data.mobile)
                    },
                   "theme": {
                        "color": "#27B53F"
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
		});

	}
});
app.controller('downloadLinkController', function ($scope, $http) {
	var data = $.param({
    });
    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;',
            'X-API-KEY': 'A1F584C3083132CE18DCDA579C753579D3276AAB'
        }
    }
    $http.post("api/ed/getOwnerUsedLicense",data,config).then(function(response)
	{
		if(response.data=="1" || response.data==1)
		{
			$("#downloadlink").addClass("hide");
		}
		else
		{
			$("#downloadlink").removeClass("hide");
		}
	});

});
app.controller("GalleryController",function($scope,$http)
{
	$scope.ascrec=0;
	$scope.userdetails = function(value) {
		var responsePromise = $http.get("services/getuserdet.php?enduserid="+value).then(function(response)
		{
				$scope.userdetp = response.data;
		}	);
	};
	$scope.GetfGallery = function(value) {
		var responsePromise = $http.get("services/gallerycount.php?enduserid="+value).then(function(response1)
		{
			document.getElementById('imagecounttot').value=response1.data;
			if(response1.data>20)
			{
				$scope.ascrec=0;
			}
			else
			{
				$scope.ascrec=1;
			}
		});
	};


});

app.controller('UserProfileController', function($scope, $http) {

	$scope.userdetails = function(value) {
		var responsePromise = $http.get("services/getuserdet.php?enduserid="+value).then(function(response)
		{
			$scope.userdetp = response.data;
		});
	};
	$scope.deleteUserData = function()
	{

		deleteInputText = $scope.deleteInputText;
		eid = $scope.eid;
		if(deleteInputText.length=="4")
		{


			var data = $.param({
	           pin: deleteInputText,
	           eid: eid
	        });
	        var config = {
	            headers : {
	                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;',
	                'X-API-KEY': 'A1F584C3083132CE18DCDA579C753579D3276AAB'
	            }
	        }
	        $http.post("api/ed/deleteAll",data,config).then(function(response)
			{
				var data = response.data;
				if(data["status"]=="success")
				{
					$("#DeleteAllModal").modal("hide");
					$("#pinError").addClass("hide");
				}
				else
				{
					$("#pinError").removeClass("hide");
	    			$("#pinError").text("Incorrect Pin");
				}
			});
	    }
	    else
	    {
	    	$("#pinError").removeClass("hide");
	    	$("#pinError").text("Must be 4 numbers.");
	    }
	};
	$scope.installedsoftware = function(eid)
	{
		$http.get("services/installedsoftware.php?eid="+eid).then(function(response)
		{
			$("#pen2-left").addClass("hide");
			$("#pen3-left").removeClass("hide");
			document.getElementById("pen3-left").innerHTML=response.data;
		});
	};
	$scope.showusertimesheet = function(enduserid, selectedmonth,name)
	{
		$scope.endusername = name;
		$http.get("services/function.php?mode=showuserwisetimesheet&enduserid="+enduserid+"&selectedmonth="+selectedmonth).then(function(response) {
			document.getElementById("usertimesheet").innerHTML=response.data;
		});
	};
});

app.controller('ChangepassController', function($scope, $http,$timeout) {
	$scope.changepassword = function() {
	var currentpassword =document.getElementById('currentpass').value;
	var changepass1 = document.getElementById('pass1').value;
	var changepass2 = document.getElementById('pass2').value;
	var responsePromise = $http.get("services/changepassword.php?currentpassword="+currentpassword+"&changepass1="+changepass1+"&changepass2="+changepass2).then(function(response)
	{
			var changepassres = response.data;

			if(changepassres=='4')
			{
				document.getElementById('currentpass').style="border:1px solid red";
				document.getElementById('pass1').style="border:0px solid red";
				document.getElementById('pass2').style="border:0px solid red";
			}
			else if(changepassres=='5')
			{
				document.getElementById('currentpass').style="border:0px solid red";
				document.getElementById('pass1').style="border:1px solid red";
				document.getElementById('pass2').style="border:0px solid red";
			}
			else if(changepassres=='6')
			{
				document.getElementById('currentpass').style="border:0px solid red";
				document.getElementById('pass1').style="border:0px solid red";
				document.getElementById('pass2').style="border:1px solid red";
			}

			else if(changepassres=='3')
			{
				document.getElementById('currentpass').style="border:0px solid red";
				document.getElementById('pass1').style="border:1px solid red";
				document.getElementById('pass2').style="border:1px solid red";
				document.getElementById('ErrorClass').innerHTML="<div class='alert-box alert1'>New Password & confirm password mismatch</div>";
			}
			else if(changepassres=='2')
			{
				document.getElementById('currentpass').style="border:1px solid red";
				document.getElementById('pass1').style="border:0px solid red";
				document.getElementById('pass2').style="border:0px solid red";
				document.getElementById('ErrorClass').innerHTML="<div class='alert-box alert1'>Current password mismatch</div>";
			}
			else if(changepassres=='7')
			{
				document.getElementById('currentpass').style="border:1px solid red";
				document.getElementById('pass1').style="border:0px solid red";
				document.getElementById('pass2').style="border:0px solid red";
				document.getElementById('ErrorClass').innerHTML="<div class='alert-box alert1'>You use this password recently. <br>Please choose a different one</div>";
			}
			else if(changepassres=='1')
			{
				document.getElementById('currentpass').style="border:0px solid red";
				document.getElementById('pass1').style="border:0px solid red";
				document.getElementById('pass2').style="border:0px solid red";
				document.getElementById('ErrorClass').innerHTML="<div class='alert-box success'>Password successfully changed</div>";
				$timeout(function() {
	      				window.location.href="logout.php";
	      			}, 3000);
			}

	});
	};
});

app.controller('TypeaheadCtrl', function($scope, $http) {

	var responsePromise = $http.get("services/getuser.php").then(function(response)
	{
		$scope.statesWithFlags = response.data;

	});


	$scope.typeproblem = function (str) {
		var responsePromise = $http.get("services/getuser.php?enduserid="+str).then(function(response)
		{
			$scope.customSelected = response.data;
		});
	};
	$scope.initialisedata = function (str) {

			$scope.searchtextbyuser = str;

	};
});


app.controller('ExampleController', function($scope,$timeout,$http) {

	var responsePromise = $http.get("services/getuser.php").then(function(response)
	{
		$scope.statesWithFlags = response.data;

	});
	$scope.savepin = function(pin)
	{
		if(pin.length!=4)
		{
			$("#pinError").removeClass("hide");
		}
		else
		{

			var data = $.param({
	            pin: pin
	        });

	        var config = {
	            headers : {
	                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;',
	                'X-API-KEY': 'A1F584C3083132CE18DCDA579C753579D3276AAB'
	            }
	        }
			$http.post("api/ed/savePin",data,config).then(function(response)
			{
				$("#pinError").addClass("hide");
				$("#currentPin").removeClass("hide");
				$("#editPin").addClass("hide");
			});
		}
	};
	$scope.getSettingValues = function(enduserid)
	{
		var data = $.param({
            enduserid: enduserid
        });

        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;',
                'X-API-KEY': 'A1F584C3083132CE18DCDA579C753579D3276AAB'
            }
        }
		$http.post("api/ed/getSettings",data,config).then(function(response)
		{
			var data = response.data;
			if(data["errormsg"]=="success")
			{
				$("#load-content").removeClass("hide");
				if (data["screenshot"] == "1") {
					$('#screenShotToggle').bootstrapToggle('on');
					$("#screenshottimeinterval").removeClass("hide");
				} else {
					$('#screenShotToggle').bootstrapToggle('off');
					$("#screenshottimeinterval").addClass("hide");
				}

				if (data["screenshotinterval"] > 0) {
					$scope.screenShotTimer = data["screenshotinterval"];
				}

				if (data["trayicon"] == "1") {
					$('#trayIconToggle').bootstrapToggle('on');
				} else {
					$('#trayIconToggle').bootstrapToggle('off');
				}

				if (data["keylog"] == "1") {
					$('#keyLogsToggle').bootstrapToggle('on');
				} else {
					$('#keyLogsToggle').bootstrapToggle('off');
				}

				if (data["webhistory"] == "1") {
					$('#webHistoryToggle').bootstrapToggle('on');
				} else {
					$('#webHistoryToggle').bootstrapToggle('off');
				}

				if (data["stealthinstall"] == "1") {
					$('#installToggle').bootstrapToggle('on');
				} else {
					$('#installToggle').bootstrapToggle('off');
				}

				if (data["pause"] == "1") {
					$('#pauseToggle').bootstrapToggle('on');
				} else {
					$('#pauseToggle').bootstrapToggle('off');
				}

				if (data["usbblock"] == "1") {
					$('#usbBlockToggle').bootstrapToggle('on');
				} else {
					$('#usbBlockToggle').bootstrapToggle('off');
				}

				if (data["taskmanagerblock"] == "1") {
					$('#taskMToggle').bootstrapToggle('on');
				} else {
					$('#taskMToggle').bootstrapToggle('off');
				}

				if (data["lazyminutes"] > 0) {
					$scope.lazyMinutesTimer = data["lazyminutes"];
				}

				if (data["pin"] != "") {
					$scope.pin = data["pin"];
				}

			}
		});
	};

	$scope.settingsToggle = function(val, enduserid, type)
	{
    	// call web service to update screenshot global value
    	// val is the toggle value of all types
    	var data = $.param({
            val: val,
            enduserid: enduserid,
            type: type
        });
    	var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;',
                'X-API-KEY': 'A1F584C3083132CE18DCDA579C753579D3276AAB'
            }
        }
		$http.post("api/ed/updateSettings",data,config).then(function(response)
		{

		});
	};

	$scope.editPin = function()
	{
		$("#currentPin").addClass("hide");
		$("#editPin").removeClass("hide");
	};
	$scope.deleteConfirm = function(pin)
	{
		console.log(pin.length);
		if(pin.length===4)
		{
			$("#pinError").addClass("hide");
			$("#confirm-delete").modal("show");
		}
		else
		{
							/* Error*/
							console.log("pavit");
			$("#pinError").removeClass("hide");
			$("#pinError").text("Must be 4 numbers");

		}
	};

	$scope.deleteAll = function(pin)
	{

		var data = $.param({
           pin: pin
        });

        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;',
                'X-API-KEY': 'A1F584C3083132CE18DCDA579C753579D3276AAB'
            }
        }
		$http.post("api/ed/deleteAll",data,config).then(function(response)
		{
			var data=response.data;
			if(data["status"]=="success")
			{
				$("#pinError").removeClass("hide");
				$("#pinError").text("Data successfully deleted.");
			}
			else
			{
				$("#pinError").removeClass("hide");
				$("#pinError").text("Incorrect Pin");
			}
			$("#confirm-delete").modal("hide");
		});
	};
	$scope.typeproblem = function (str) {
		var responsePromise = $http.get("services/getuser.php?enduserid="+str).then(function(response)
		{
			$scope.customSelected = response.data;
		});
	};
	$scope.initialisedata = function (str) {
		$scope.searchtextbyuser = str;
	};
	$scope.userdetails = function(value) {
		var responsePromise = $http.get("services/getuserdet.php?enduserid="+value).then(function(response)
		{

				$scope.userdetp = response.data;
		});
	};
	var responsePromise = $http.get("services/getuser.php").then(function(response)
		  {
	  			$scope.users = response.data;

		  }
	);
	$scope.search = [];
	$scope.text = '';
	$scope.tt=true;
	$scope.tt1=false;




$scope.countries ='[{"id":"101","value":"1001","label":"Lalithcellgell"},{"id":"102","value":"1001","label":"Pavitcellgell"},{"id":"103","value":"1001","label":"MukeshCellgell"},{"id":"110","value":"1002","label":"SujeetCellgell"},{"id":"111","value":"1002","label":"RajanCellgell"}]' ;

$scope.open = function () {

    var modalInstance = $modal.open({
      templateUrl: 'imageuploader.php',
      resolve: {
        items1: function () {
          return $scope.items1;
        }
      }
    });

    modalInstance.result.then(function (selectedItem) {
      $scope.selected = selectedItem;
    }, function () {
      $log.info('Modal dismissed at: ' + new Date());
    });
  };

$scope.uploadimage= function(str)
{
	$scope.homedatamax= parseInt(maxrec)+1;
};






});

/* licenseactivation controller start */
app.controller('LicenceActivationCtrl', function ($scope,$http,$window, $timeout) {
	$scope.availablelicense = 0;
	$scope.pendingactivations = 0;
	$scope.successcount =0;
	$scope.Failurecount=0;
	$scope.pendingusers=null;
	$scope.buttondisabled=true;
	$scope.authandterms = false;
	$scope.alerterror = true;

	var dataString 	= "mode=liccount";

	/* Interval message */
	var messageTimer = false,
    displayDuration =3000; // milliseconds (5000 ==> 5 seconds)

			$scope.showMessage = false;
			$scope.failcount = false;
			$scope.msg = "";

			$scope.ShowAlertMes = function () {
			    if (messageTimer) {
			        $timeout.cancel(messageTimer);
			    }

			    $scope.showMessage = true;
			    if($scope.Failurecount > 0)
			    	{$scope.failcount = true;}
			    else
			    	{$scope.failcount = false;}

			    messageTimer = $timeout(function () {
			        $scope.showMessage = false;
			        $scope.failcount = false;
			    }, displayDuration);
			};

			/* Interval message */
	var responsePromise= $http.get("services/licenseactivation.php?"+dataString).then(function(response){
		    $scope.availablelicense = response.data;
	 });
	var responsePromise= $http.get("services/licenseactivation.php").then(function(response){
		    $scope.pendingactivations = response.data.length;
		    $scope.pendingusers=  response.data;
	 });

    $scope.DeleteUsers = function()
    {
    	$scope.successcount =0;
    	$scope.Failurecount=0;
    	     $scope.msg="";
		     angular.forEach($scope.pendingusers, function (item) {
		     if(item.Selected==true)
		    	 {
		    	 var dataString	= "mode=deleteuser&userid="+item.id;

		    	 var responsePromise= $http.get("services/licenseactivation.php?"+dataString).then(function(response){

		    		 if(response.data==1)
		 			   {
		 			     $scope.successcount+=1;
		 			     $scope.chkcount -=1;
		 			   }
		 		   else
		 			   {
		 			     $scope.Failurecount+=1;
		 			   }
		 	 });
	 }
      		});
		    $scope.msg = " user(s) are deleted successfully!.";
		    $scope.msgfail = "user(s) are failed to delet!.";
		     var responsePromise= $http.get("services/licenseactivation.php").then(function(response){
				    $scope.pendingactivations = response.data.length;
				    $scope.pendingusers=  response.data;
			 });
		  	$scope.ShowAlertMes();

    }


	$scope.ActiveUsers = function()
	{
		$scope.msg="";
		$scope.successcount = 0;
		$scope.Failurecount = 0;
		     angular.forEach($scope.pendingusers, function (item) {
		     	 if(item.Selected==true)
		    	 {
			    	var dataString	= "mode=edituser&userid="+item.id;
			    	var responsePromise= $http.get("services/licenseactivation.php?"+dataString).then(function(response){
		    	 		if(response.data==1)
		 			    {
		 			     	$scope.successcount =  $scope.successcount + 1;
		 			     	$scope.chkcount -=1;
		 			   	}
			 		    else if(response.data==0)
		 			    {
		 			     	$scope.Failurecount-=1;
		 			    }
		 	 		});

		    	 }

      		});


			   $scope.msg = " user(s) are Active Successfully!.";
			 $scope.msgfail = "user(s) are failed to Active!.";

		     var responsePromise= $http.get("services/licenseactivation.php?"+dataString).then(function(response){
		    	 	$scope.availablelicense = response.data;
			 });
		     var responsePromise= $http.get("services/licenseactivation.php").then(function(response){
		     		$scope.pendingactivations = response.data.length;
				    $scope.pendingusers=  response.data;
			 });

		     $scope.ShowAlertMes();

	}
	$scope.chkcount=0;
	$scope.checkAll = function () {
		$scope.chkcount=0;

			  if ($scope.selectedAll) {
			      $scope.selectedAll = false;
			  } else {
			      $scope.selectedAll = true;
			  }

			      angular.forEach($scope.pendingusers, function (item) {
			      item.Selected = $scope.selectedAll;
			      $scope.chkcount+=1;
	      		});

			     if($scope.selectedAll == false)
				  {
				  $scope.chkcount=0;
				  }

			      if(($scope.chkcount < $scope.availablelicense) && ($scope.chkcount !=0) && $scope.authandterms==true )
					{
					 $scope.buttondisabled=false;
					}
			       else
				   {
					   $scope.buttondisabled=true;
				   }
			      if(($scope.buttondisabled==true) && ($scope.chkcount > 0))
				   {
				   $scope.alerterror = false;
				   }
			     else
				   {
				   $scope.alerterror = true;
				   }


	};
	$scope.authterms = function()
	{

		    if((document.getElementById('chklicauth').checked == true) && (document.getElementById('chkterms').checked==true) && ($scope.chkcount < $scope.availablelicense) && ($scope.chkcount !=0))
			{
		    	$scope.authandterms = true;
		    	 $scope.buttondisabled=false;

			}
		    else
		    {
		    	$scope.authandterms = false;
		    	 $scope.buttondisabled=true;

		   	}

		    if(($scope.buttondisabled==true) && (document.getElementById('chklicauth').checked == true || document.getElementById('chkterms').checked==true))
			   {
			   $scope.alerterror = false;
			   }
		     else
			   {
			   $scope.alerterror = true;
			   }


	}
	$scope.checkcount = function(id,event)
	{
		  var chkid = event.target.id;
		   if(document.getElementById(chkid).checked == true)
			{
			 $scope.chkcount +=1;
			}
		   else
			{
			   $scope.chkcount -=1;
			}
		   if(document.getElementById('chklicauth').checked == true && document.getElementById('chkterms').checked==true)
			   {
			   $scope.authandterms=true;
			   }
		   else
			   {
			   $scope.authandterms=false;
			   }
		   if(($scope.chkcount < $scope.availablelicense) && ($scope.chkcount !=0)  && $scope.authandterms==true )
			{
			 $scope.buttondisabled=false;

			}
		   else
		   {
			   $scope.buttondisabled=true;

		   }

		    if(($scope.buttondisabled==true) && ($scope.chkcount > 0))
			   {
			   $scope.alerterror = false;
			   }
		     else
			   {
			   $scope.alerterror = true;
			   }

	}
});

/* licenseactivation controller end */

/* usermapping controller */
app.controller('UserMapping', function ($scope,$timeout,$http) {
$scope.accordion = {
	      current: null
	    };

$scope.frmsubmit = function() {
alert($scope.name);
};

/* Interval message */
var messageTimerusers = false,
displayDurationusers =3000; // milliseconds (5000 ==> 5 seconds)

		$scope.showMessageusers = false;
		$scope.msg = "";

		$scope.ShowAlertMesusers = function () {
		    if (messageTimerusers==true) {
		        $timeout.cancel(messageTimerusers);
		    }
		    $scope.showMessageusers = true;
		    messageTimerusers = $timeout(function () {
		        $scope.showMessageusers = false;
		    }, displayDurationusers);
		};

		/* Interval message */


$scope.errormsg="";

var responsePromise= $http.get("services/usermapping.php").then(function(response){
	$scope.GetUsers = response.data;
});
var groupPromise= $http.get("services/addgroup.php?getuser=1").then(function(response){

	$scope.Groups = response.data;
});
$scope.modalShown = false;
$scope.modalShown1 = false;
$scope.toggleModal = function() {
  $scope.modalShown = !$scope.modalShown;
};
$scope.toggleModal1 = function() {
	  $scope.modalShown1 = !$scope.modalShown1;
	};
$scope.addgroup = function(str)
{

	var responsePromise = $http.get("services/addgroup.php?groupname="+str).then(function(response)
	{

		if(response.data=='0')
		{
				$scope.groupnameerror = "Already Exist";
		}
		else
		{
			$scope.groupnameerror = "Group successfully added";
			$scope.newgroup={"id":response.data,"groupname":str};
			$scope.Groups.push($scope.newgroup);
		}

	});



	}
$scope.submit = function(userid,name,desg,dept,groupname)
{
	var userid 		= userid;
	var groupname 	= groupname;
	var endusername = name;
	var department 	= dept;
	var designation	= desg;


	var dataString 	= "mode=edituser&userid="+userid+"&endusername="+endusername+"&department="+department+"&designation="+designation+"&groupname="+groupname;
	var responsePromise= $http.get("services/usermapping.php?"+dataString).then(function(response){

	if(response.data=='Profile Updated')
	{
		$scope.accordion = {
		      current: null
    };
		var responsePromise= $http.get("services/usermapping.php").then(function(response){
		    	$scope.GetUsers = response.data;
		    });

	}
	});
	  $scope.msg = " Changes are made successfully on " + name + " !.";
	  $scope.ShowAlertMesusers();
}
$scope.deleteduser = function(id,name)
{
	var r = confirm("Are you sure want to delete "+ name+" !");
	if (r == true) {
		var userid = id;
  	var dataString 	= "mode=delete&userid="+userid;
  	var responsePromise= $http.get("services/usermapping.php?"+dataString).then(function(response){
      		var responsePromise= $http.get("services/usermapping.php").then(function(response){
      		    	$scope.GetUsers = response.data;
      		    });
      		  $scope.msg = name +" is deleted successfully !." ;
      		  $scope.ShowAlertMesusers();

      	});
  }

}

$scope.resumeorpass = function(id,status)
{
	dlg = confirm('Please Confirm ,Are you sure to resume/pass?','');
	if(dlg==true)
		{
		var userid = id;
		var active = status;
		var dataString 	= "mode=useract&userid="+userid+"&active="+active;
		var responsePromise= $http.get("services/usermapping.php?"+dataString).then(function(response){
	  		var responsePromise= $http.get("services/usermapping.php").then(function(response){
	  		   	$scope.GetUsers = response.data;
	  		    });
	  		 $scope.msg = name +"User resume/Pass successfully !." ;
     		  $scope.ShowAlertMesusers();
	});
		}
}
});


/* end user mapping controller */

app.controller('ActivityCtrl', function ($scope,$timeout,$http, $filter) {
	$scope.currentdate = new Date();
	$scope.errormsg="";
	$scope.errormsgshow =false;
	$scope.fg="1";
	$scope.filteractivity = function(iem) {
		$scope.GetAllLatestUsers = iem;
		if($scope.fg=="1")
	    {
	    	$scope.GetLatestUsers = $filter('filter')($scope.GetAllLatestUsers,{status:'1'},true);
			$("#activeuserslist").addClass("gactive");
			$("#idleuserslist").removeClass("yactive");
			$("#offlineuserslist").removeClass("ractive");
	    }
	    else if($scope.fg=="2")
	    {
	    	$scope.GetLatestUsers = $filter('filter')($scope.GetAllLatestUsers,{status:'2'},true);
			$("#activeuserslist").removeClass("gactive");
    		$("#idleuserslist").addClass("yactive");
    		$("#offlineuserslist").removeClass("ractive");
	    }
	    else if($scope.fg=="3")
	    {
	    	$scope.GetLatestUsers = $filter('filter')($scope.GetAllLatestUsers,{status:'3'},true);
			$("#activeuserslist").removeClass("gactive");
    		$("#idleuserslist").removeClass("yactive");
    		$("#offlineuserslist").addClass("ractive");
	    }
		$scope.$apply();
	}
    $scope.edituser = function(event){

    };
    $scope.imageProcess = function(id)
	{
		alert("fr"+id);
	};
    $scope.userprofile=function(sno)
    {
    	window.location.href="userprofile.php?enduserid="+sno;
    };

    $scope.showactiveusers = function(fg) {
    	var y = document.getElementById("countahead").value;
    	document.getElementById("latestuserliststatus").value=fg;
    	z=y+1;
    	$scope.errormsgshow =false;
    	$("#userlistLoad").removeClass("hide");
    	$("#userlistOnline").addClass("hide");
    	if(fg=="1")
    	{
    		$("#activeuserslist").addClass("gactive");
    		$("#idleuserslist").removeClass("yactive");
    		$("#offlineuserslist").removeClass("ractive");
    	}
    	else if(fg=="2")
    	{
    		$("#activeuserslist").removeClass("gactive");
    		$("#idleuserslist").addClass("yactive");
    		$("#offlineuserslist").removeClass("ractive");
    	}
    	else if(fg=="3")
    	{
    		$("#activeuserslist").removeClass("gactive");
    		$("#idleuserslist").removeClass("yactive");
    		$("#offlineuserslist").addClass("ractive");
    	}
    	var url = "services/enduser_leftbar_status.php?mode=abc&fg="+fg;

		var responsePromise= $http.get(url).then(function(response){
			$scope.GetLatestUsers="";
			if(parseInt(response.data.length)>0)
			{
				leg=response.data.length;
	        	$scope.GetLatestUsers = response.data;
	        	$("#userlistLoad").addClass("hide");
	        	$("#userlistOnline").removeClass("hide");
	        	if(fg=="1")
        		{
        			if(leg=="1" || leg==1)
        			{
        				$scope.jhi = leg+" user online";
        			}
        			else
        			{
        				$scope.jhi = leg+" users online";
        			}
        		}
        		if(fg=="2")
        		{
        			if(leg=="1" || leg==1)
        			{
        				$scope.jhi = leg+" user idle";
        			}
        			else
        			{
        				$scope.jhi = leg+" users idle";
        			}
        		}
        		if(fg=="3")
        		{
        			if(leg=="1" || leg==1)
        			{
        				$scope.jhi = leg+" user offline";
        			}
        			else
        			{
       	 				$scope.jhi = leg+" users offline";
       	 			}
        		}
        	}
        	else
        	{
        		if(fg=="1")
        		{
        			$scope.jho = "No online user";
        		}
        		if(fg=="2")
        		{
        			$scope.jho = "No idle user";
        		}
        		if(fg=="3")
        		{
       	 			$scope.jho = "No offline user";
        		}
        		$("#userlistLoad").addClass("hide");
	        	$("#userlistOnline").removeClass("hide");
        	}

        });
       document.getElementById("countahead").value=z;

      	//mytimeout = $timeout($scope.onTimeout,30000);
    };
	
    $scope.showactiveusersreload = function() {
    	var y = document.getElementById("countahead").value;
    	fg=document.getElementById("latestuserliststatus").value;
    	z=y+1;
    	$scope.errormsgshow =false;
    	$("#userlistLoad").removeClass("hide");
    	$("#userlistOnline").addClass("hide");
    	if(fg=="1")
    	{
    		$("#activeuserslist").addClass("gactive");
    		$("#idleuserslist").removeClass("yactive");
    		$("#offlineuserslist").removeClass("ractive");
    	}
    	else if(fg=="2")
    	{
    		$("#activeuserslist").removeClass("gactive");
    		$("#idleuserslist").addClass("yactive");
    		$("#offlineuserslist").removeClass("ractive");
    	}
    	else if(fg=="3")
    	{
    		$("#activeuserslist").removeClass("gactive");
    		$("#idleuserslist").removeClass("yactive");
    		$("#offlineuserslist").addClass("ractive");
    	}
    	var url = "services/enduser_leftbar_status.php?mode=abc&fg="+fg;

		var responsePromise= $http.get(url).then(function(response){
			$scope.GetLatestUsers="";
			if(parseInt(response.data.length)>0)
			{
				leg = response.data.length;
	        	$scope.GetLatestUsers = response.data;
	        	$("#userlistLoad").addClass("hide");
	        	$("#userlistOnline").removeClass("hide");
	        	if(fg=="1")
        		{
        			if(leg=="1" || leg==1)
        			{
        				$scope.jhi = leg+" user online";
        			}
        			else
        			{
        				$scope.jhi = leg+" users online";
        			}
        		}
        		if(fg=="2")
        		{
        			if(leg=="1" || leg==1)
        			{
        				$scope.jhi = leg+" user idle";
        			}
        			else
        			{
        				$scope.jhi = leg+" users idle";
        			}
        		}
        		if(fg=="3")
        		{
        			if(leg=="1" || leg==1)
        			{
        				$scope.jhi = leg+" user offline";
        			}
        			else
        			{
       	 				$scope.jhi = leg+" users offline";
       	 			}
        		}
        	}
        	else
        	{
        		if(fg=="1")
        		{
        			$scope.jho = "No online user";
        		}
        		if(fg=="2")
        		{
        			$scope.jho = "No idle user";
        		}
        		if(fg=="3")
        		{
       	 			$scope.jho = "No offline user";
        		}
        		$("#userlistLoad").addClass("hide");
	        	$("#userlistOnline").removeClass("hide");
        	}

        });
       document.getElementById("countahead").value=z;

      	//mytimeout = $timeout($scope.onTimeout,30000);
    };
});
function DefaultCtrl($scope) {
    $scope.names = ["john", "bill", "charlie", "robert", "alban", "oscar", "marie", "celine", "brad", "drew", "rebecca", "michel", "francis", "jean", "paul", "pierre", "nicolas", "alfred", "gerard", "louis", "albert", "edouard", "benoit", "guillaume", "nicolas", "joseph"];
}

angular.module('MyModule', []).directive('autoComplete', function($timeout) {
    return function(scope, iElement, iAttrs) {
            iElement.autocomplete({
                source: scope[iAttrs.uiItems],
                select: function() {
                    $timeout(function() {
                      iElement.trigger('input');
                    }, 0);
                }
            });
    };
});
