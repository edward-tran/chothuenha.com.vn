<?php
    class ControllerAccountUpload extends Controller{
        public function uploadAvatar(){
            $json =array();
            $json['status']=true;

            file_put_contents('image.txt', json_encode($_FILES));

            // echo json_encode($_FILES);
            // die;

            $target_dir = DIR_HOME.'image/avatar/';
            if (!isset($_FILES['avatar'])){
                $json['error_file'] = "Vui long chon file";
            }else{
                $name = basename($_FILES['avatar']['name']);
                $target_file = $target_dir . $name;
                
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                //$check = getimagesize($_FILES['file']['tmp_name']);
                //file_put_contents('log.txt', json_encode($check));
                $maxSize = 2000000;
                if ($_FILES['avatar']['size'] <= $maxSize){
                    $json['status'] = "File is an image";

                    $log = new Log('tuananh.txt');
                    $log->write($target_dir);
                    
                    if (!file_exists($target_dir)){
                        mkdir($target_dir, 0777);
                    }
                    file_put_contents('target.txt', $target_file);
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)){
                        $json['message'] = "The file has been uploaded.";
                        $json['url_image'] = URL_HOME.'image/avatar/'.$name;
                        $this->load->model('account/customer');
                        $image = 'avatar/'.$name;
                        $customer_id = $this->customer->getId();
                        $this->model_account_customer->updateImageByIdCustomer($image, $customer_id);
                    }else{
                        $json['error_file'] = "Failed to upload file.";
                    }
                   
                }else{
                    $json['status'] = false;
                    $json['error_file']="Kich thuoc file qua lon";
                }
            }
            echo json_encode($json);

        }
        

    }

