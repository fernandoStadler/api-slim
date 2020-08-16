<?php
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

$app->group("/user", function($app){

  

    ####################INSERT################################################################
    $app->post("/insert", function(Request $request, Response $response, array $args){

        $data = $request->getParsedBody();

        $obrigatorios =["name","surname","user_name","mail","phone","password","nickname"];
        
        $errors=[];
        foreach($obrigatorios as $campo):
            if(!isset($data[$campo]) || strlen($data[$campo])===0)
            $errors[]="Campo vazio {$campo}";
        endforeach;

        if(!empty($errors))return $response->withJson($errors);

        
        ###############################
       #            DATA              #
       ###############################
     
        $user_name = $data ['user_name'];
        $name = $data ['name'];
        $surname = $data ['surname'];
        $mail = $data ['mail'];
        $phone = $data ['phone'];
        $password = $data ['password'];
        $nickname = $data ['nickname'];
        ###############################

        if(empty($udid)) $udid=null;
    
        $json=$data;
    
        //DB CONNECT
        $db = new Login\Connect();
 
        //QUERY
            $query = "INSERT INTO users (
                `uuid`,
                `name`,
                `user_name`,
                `surname`,
                `mail`,
                `phone`,
                `password`,
                `nickname`
                )
                VALUE(
                    UUID(),
                    '$name',
                    '$user_name',
                    '$surname',
                    '$mail',
                    '$phone',
                    md5('$password'),
                    '$nickname'
                    )";
    
        ###########################RESULT############################################
        $result = $db->query($query);
    
        return $response->withJson($result);
    
    });
    ######################SELECT###############################################################
    $app->get("/list[/{id}]", function(Request $request, Response $response, array $args){
     
        $db = new Login\Connect();
      
        
        if(empty($args)) $args=null;
        
        
        #############QUERY#######################
       

        $query = "SELECT * FROM users WHERE `active` = '1'";
        if(!empty($id)) $query .=" AND id=$id";

        $result = $db->query($query);
        
        $result->fetch_row();
        $users = [];

        ################ROWS#####################
        foreach($result as $user):
            $users[] = $user;
            
        endforeach;
       
        return $response->withJson($users);

    });
    // ######################ALTER###############################################################
    $app->put("/update/{id}", function(Request $request, Response $response, array $args){

        
        $id = preg_replace("/[^0-9]/","",$args["id"]);

        $data = $request->getParsedBody();
        $db = new Login\Connect();

        $obrigatorios =["name","surname","user_name","mail","phone","password","nickname"];
        
        $errors=[];
        foreach($obrigatorios as $campo):
            if(!isset($data[$campo]) || strlen($data[$campo])===0)
            $errors[]="Campo vazio {$campo}";
        endforeach;

        if(!empty($errors))return $response->withJson($errors);

    
        $name = $data ['name'];
        $surname = $data ['surname'];
        $user_name = $data ['user_name'];
        $mail = $data ['mail'];
        $phone = $data ['phone'];
        $password = $data ['password'];
        $nickname = $data ['nickname'];
     
        
        $json= $data;

        foreach ($data as $key => $value) :
            if ((!is_int($value) || !is_float($value)) && strpos($value, "('") === false && $value !== "NULL")
                $value = "'" . trim($value) . "'";

            $query_fields[] = "`{$key}` = {$value}";
        endforeach;

        $query_fields = " SET " . implode(", ", $query_fields);

        $query = "UPDATE users {$query_fields} WHERE `id`='$id'";
       
        $result = $db->query($query);
        return $response->withJson($result);

    
    });
    // ######################DELETE###############################################################
    
    $app->delete("/delete/{id}", function(Request $request, Response $response, array $args){

        $id = preg_replace("/[^0-9]/","",$args["id"]);
        
        $data = $request->getParsedBody();
      
        
        
        $db = new Login\Connect();

        $query = "UPDATE users SET `active`=0, `deleted_at`=now()  WHERE id=$id";
        $result= $db->query($query);

        return $response->withJson("Deletado com sucesso!");
    
    });
    // ######################REACTIVATE###############################################################
    
    $app->put("/activate/{id}", function(Request $request, Response $response, array $args){

        $id = preg_replace("/[^0-9]/","",$args["id"]);
        
        $data = $request->getParsedBody();
      
        
        
        $db = new Login\Connect();

        $query = "UPDATE users SET `active`=1, `reactivate_at`=now()  WHERE id=$id";
        $result= $db->query($query);

        return $response->withJson("Reativado com sucesso!");
    
    });


    ########################LOGIN###########################################################
    $app->post("/login", function(Request $request, Response $response, array $args){

        
        $data = $request->getParsedBody();
     
        $db = new Login\Connect();

        $password = $data ['password'];
        $security_password =  md5($password);
        $user_name = $data ['user_name'];

        if(empty($args)) $args=null;
        
        #############QUERY#######################
       

        $query = "SELECT user_name, mail, phone, nickname, created_at, reactivate_at,deleted_at,active FROM users WHERE `active` =1  AND `user_name`='$user_name' AND `password`='$security_password'";
       

        $result = $db->query($query);
        
        $result->fetch_object();
        $users = [];

        ################ROWS#####################
        foreach($result as $user):
            $users[] = $user;
        endforeach;
        
      

        return $response->withJson($users);


    });
    
});

?>