<?php

include_once "../../app/Database.php";
include_once "../../app/App.php";
include_once "../../app/Auth.php";
$auth = new \app\Auth();
$app = new \app\App();
$d = $app->fileHistory($_POST['form_id']);

if($d){
    echo '<ul class="timeline mt-2 mb-0">';
    foreach ($d as $item){

        //if reviewed
        $r='';
        //c=complied
        $c='';
        if($item['is_reviewed']=='reviewed'){
            $r='<span class="badge bg-primary"><span class="fa fa-check-circle"></span> Reviewed</span>';
            if($item['is_complied']=='complied'){
                $c='<span class="badge bg-success"><span class="fa fa-check-circle"></span> Complied</span>';
            }else{
                $c='<span class="badge bg-danger"><span class="fa fa-times-circle"></span> Not Complied</span>';
            }
        }
        if($item['is_reviewed']=='for review'){
            $r='<span class="badge bg-warning"><span class="fa fa-exclamation-circle"></span> For review</span>';
        }

        echo '<li class="timeline-item">
                <strong><a href="'.$item['host'].$item['file_path'].'" target="_blank">'.$item['original_filename'].'</a></strong>
                <br/>
                '.$r.' '.$c.'
                <br>
                <span>Date: '.$item['date_uploaded'].'</span>
                <br>
                <span class="pb-4">RP: '.$item['responsible_person'].'</span>
                </p>
                </li>';
        echo '</div>';
    }
}else{
    echo '<div class="text-center m-3">No records found</div>';
}
