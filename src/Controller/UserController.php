<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\Request;
use App\Repository\UserRepository;
use App\Entity\User;
use PDOException;

class UserController extends AbstractController{
    public function __construct(
        //user repo en privée + notion de service
    ) {}

    public function userSignup(){
        $title = "boop";
        $request = new Request();

        if(isset($_SESSION["user"])){
            header("Location:../");
            exit;
        }

        if ( $request->getMethod() !=="POST" || empty($_POST)) {
            $this->render('user/signup.php',["title"=>$title]);
        } else {
            $args = [
                "nom" => [
                    "filter" => FILTER_VALIDATE_REGEXP,
                    "options" => [
                        "regexp" => "#^[\w\s-]+$#u"
                    ]
                ],
                "prenom" => [
                    "filter" => FILTER_VALIDATE_REGEXP,
                    "options" => [
                        "regexp" => "#^[\w\s-]+$#u"
                    ]
                ],
                "genre"=>[
                    "filter" => FILTER_VALIDATE_REGEXP,
                    "options" => [
                        "regexp" => "#^[\w\s-]+$#u"
                    ]
                ],
                "date_naissance"=>[],
                "email" => [
                    "filter" => FILTER_VALIDATE_EMAIL
                ],
                "mot_passe" => [
                    "filter" => FILTER_VALIDATE_REGEXP,
                    "options" => [
                        "regexp" => "#^[\w\s-]+$#u"
                    ]
                ],
                "date_creation"=>[],
                "adresse" => [
                    "filter" => FILTER_VALIDATE_REGEXP,
                    "options" => [
                        "regexp" => "#^[\w\s-]+$#u"
                    ]
                ],
                "cp" => [
                    "filter" => FILTER_VALIDATE_REGEXP,
                    "options" => [
                        "regexp" => "#^[\w\s-]+$#u"
                    ]
                ],
                "ville" => [
                    "filter" => FILTER_VALIDATE_REGEXP,
                    "options" => [
                        "regexp" => "#^[\w\s-]+$#u"
                    ]
                ],
                "tel" => [
                    "filter" => FILTER_VALIDATE_REGEXP,
                    "options" => [
                        "regexp" => "#^[\w\s-]+$#u"
                    ]
                ]
            ];
        
            $signup_post = filter_input_array(INPUT_POST, $args);
            
            
            foreach($signup_post as $key => $test) {
               if($test === false || empty(trim($test))) {$error_messages[]= "$key invalide" ;};
            }
            
            if(empty($error_messages)) {
                try{
                        
                        $userDao = new UserRepository();
                        $exist_user = $userDao->getUserByEmail($signup_post["email"]);
                        //dd($exist_user);

                        //Possible réduction ?
                        if (is_null($exist_user)) {
                            $signup_user = (new User())
                            ->setNom($signup_post["nom"])
                            ->setPrenom($signup_post["prenom"])
                            ->setGenre($signup_post["genre"])
                            ->setDate_naissance($signup_post["date_naissance"])
                            ->setEmail($signup_post["email"])
                            ->setmot_passe(password_hash($signup_post["mot_passe"],PASSWORD_DEFAULT))
                            ->setAdresse($signup_post["adresse"])
                            ->setCp($signup_post["cp"])
                            ->setVille($signup_post["ville"])
                            ->setTel($signup_post["tel"]);

                        $userDao->addUser($signup_user);
                        header("Location:../");
                        exit;
                        }else{
                            $error_messages[] = "Cet email est déjà utilisé";
                      
                            $this->render('user/signup.php',[
                                "errors" => $error_messages
                                ,"title"=>$title

                            ]);
                        }
                    } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            } else {
                $this->render('user/signup.php',["errors" => $error_messages,"title"=>$title]);
            } 
            /*else if ($request->getMethod() === null ) {
                $this->render('error/error_page.php',["title=>$title_error"])
            }*/
        }

    }

    public function userSignin(){
        $title = "bloop";
        $request = new Request();

        if (isset($_SESSION["user"])) {
            header("Location:");
            exit;
        }
        
        if ($request->getMethod() !=="POST" || empty($_POST)) {
            //include TEMPLATES . DIRECTORY_SEPARATOR . "signin.php";
            $this->render('user/signin.php',["title"=>$title]);
        } else {
            $args = [
                "email" => [
                    "filter" => FILTER_VALIDATE_EMAIL
                ],
                "mot_passe" => []
            ];
        
            $signin_user = filter_input_array(INPUT_POST, $args);

            foreach($signin_user as $key => $test) {
                if($test === false || empty(trim($test))) {$error_messages[]= "$key invalide" ;};
             }
        
            if (empty($error_messages)) {
                $signin_user = (new User())
                    ->setEmail($signin_user["email"])
                    ->setmot_passe($signin_user["mot_passe"]);

                
        
                try {
                    $userDao = new UserRepository();
                    $user = $userDao->getUserByEmail($signin_user->getEmail());
                    if (!is_null($user)) {
                        
                        if (password_verify(
                            $signin_user->getmot_passe(),
                            $user->getmot_passe())) {
                            $user = $userDao->getUserById($user->getId_user());
                            
                            session_regenerate_id(true);
                            $_SESSION["user"] = $user;
                            header("Location:../");
                            exit;
                        } else {
                            $error_messages[] = "Mot de passe erroné";
                            $this->render('user/signin.php',["errors" => $error_messages,"title"=>$title]);
                        }
                    } else {
                        $error_messages[] = "Email erroné";
                        $this->render('user/signin.php',["errors" => $error_messages,"title"=>$title]);
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            } else {
                $this->render('user/signin.php',["title"=>$title]);
            }
        }
    }

    public function userSignout() {
        session_destroy();
        unset($_SESSION);
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            null,
            strtotime('yesterday'),
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );

        header("Location:signin");
        exit;
    }

    public function userShow($user_id){
        $title = "bleep";
        if ($user_id !== false) {
            try {
                $userDao = new UserRepository();
                $user = $userDao->getUserById($user_id);
                
                if (!is_null($user)) {
                    $this->render('user/show_user.php',["user" => $user,"title"=>$title]);
                } else {
                    header("Location:../");
                    exit;
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        } else {
            header("Location:../");
            exit;
        }
    }

    public function userUpdate($user_id){
        $title = "Editer un utilisateur";
        $request = new Request();

        if ($user_id !== false) {
            try {
                $userDao = new UserRepository();
                $user = $userDao->getUserById($user_id);
               
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        
            if ($request->getMethod() !=="POST" || empty($_POST)) {
                if (!is_null($user)) {
                    $this->render('user/edit_user.php',["user" => $user,"title"=>$title]);
                } else {
                    header("Location:../");
                    exit;
                }
            } else {
                $args = [
                    "nom" => [
                        "filter" => FILTER_VALIDATE_REGEXP,
                        "options" => [
                            "regexp" => "#^[\w\s-]+$#u"
                        ]
                    ],
                    "prenom" => [
                        "filter" => FILTER_VALIDATE_REGEXP,
                        "options" => [
                            "regexp" => "#^[\w\s-]+$#u"
                        ]
                    ],
                    "genre"=>[],
                    "date_naissance"=>[],
                    "email" => [
                        "filter" => FILTER_VALIDATE_EMAIL
                    ],
                    "mot_passe" => [],
                    "date_creation"=>[],
                    "adresse" => [
                        "filter" => FILTER_VALIDATE_REGEXP,
                        "options" => [
                            "regexp" => "#^[\w\s-]+$#u"
                        ]
                    ],
                    "cp" => [
                        "filter" => FILTER_VALIDATE_REGEXP,
                        "options" => [
                            "regexp" => "#^[\w\s-]+$#u"
                        ]
                    ],
                    "ville" => [
                        "filter" => FILTER_VALIDATE_REGEXP,
                        "options" => [
                            "regexp" => "#^[\w\s-]+$#u"
                        ]
                    ],
                    "tel" => [
                        "filter" => FILTER_VALIDATE_REGEXP,
                        "options" => [
                            "regexp" => "#^[\w\s-]+$#u"
                        ]
                    ]
                ];
        
                $edit_post = filter_input_array(INPUT_POST, $args);
        
                foreach($edit_post as $key => $test) {
                    if($test === false || empty(trim($test))) {$error_messages[]= "$key invalide" ;};
                 }
        
                if (empty($error_messages)) {
        
                    $edit_user = (new User())
                        ->setId_user($user_id)
                        ->setNom($edit_post["nom"])
                        ->setPrenom($edit_post["prenom"])
                        ->setGenre($edit_post["genre"])
                        ->setDate_naissance($edit_post["date_naissance"])
                        ->setEmail($edit_post["email"])
                        ->setmot_passe(password_hash($edit_post["mot_passe"], PASSWORD_DEFAULT))
                        ->setAdresse($edit_post["adresse"])
                        ->setCp($edit_post["cp"])
                        ->setVille($edit_post["ville"])
                        ->setTel($edit_post["tel"]);
        
                    try {
                        $userDao->updateUser($edit_user);
                        header("Location:show");
                        exit;
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                } else {
                    try {
                        $userDao = new UserRepository();
                        $user = $userDao->getUserById($user_id);
                        
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                    $this->render('user/edit_user.php',["user" => $user,"errors"=>$error_messages,"title"=>$title]);
                }
            }
        } else {
            header("Location:../");
            exit;
        }
    }
}
