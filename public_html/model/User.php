<?php require_once(__DIR__."/../connection.php");?>
<?php require_once(__DIR__."/../model/Member.php"); ?>
<?php 

    $conn = makeConnection();

    class User {
        const QUERY = 'SELECT id, username, password as passwordHash, active, admin, profile as profileID FROM users WHERE username = ?';
        
        private $id;
        private $passwordHash;
        private $active;
        private $admin;
        private $profileID;
        
        public $profile;

        function __construct($username) {
            $this->username = $username;

            $SQL = $GLOBALS["conn"]->prepare(self::QUERY);
            $SQL->execute(array($this->username));

            $SQL->bindColumn('id', $this->id);
            $SQL->bindColumn('username', $this->username);
            $SQL->bindColumn('passwordHash', $this->passwordHash);
            $SQL->bindColumn('active', $this->active);
            $SQL->bindColumn('admin', $this->admin);
            $SQL->bindColumn('profileID', $this->profileID);

            $result = $SQL->fetchAll(PDO::FETCH_BOUND);
            
            if (count($result) !== 1) {                
                throw new Exception("Exception: User Not Found");
            }
        }

        function id_getter() {
            return $this->id;
        }

        function active_getter() {
            return $this->id;
        }

        function admin_getter() {
            return $this->admin;
        }

        function username_getter() {
            return $this->username;
        }

        function profileID_getter() {
            return $this->profileID;
        }

        function passwordHash_getter() {
            return $this->passwordHash;
        }

        function toJSON($encode = TRUE) {
            
            $response = array();
            $response['type'] = "USER";
            $response['username'] = $this->username_getter();
            $response['passwordHash'] = $this->passwordHash_getter();
            $response['active'] = $this->active_getter();
            
            if ($encode) {
                return json_encode($response);
            } else {
                return $response;
            }
        }
        
        function isActive () {
            if ($this->active == 1) {
                return true;
            } else {
                return false;
            }
        }
        
        function isAdmin () {
            if ($this->admin == 1) {
                return true;
            } else {
                return false;
            }
        }
        
        function hydrateProfile () {
            if ($this->profileID) {
                $this->profile = new Member($this->profileID);
            }
        }

        function claimProfile ($profile) {
            if ($this->profileID) throw new Exception("Usuário Já Reivindicou um Perfil.");

            $claimQuery = "UPDATE users SET profile = ? WHERE users.id = ?";
            $SQL = $GLOBALS["conn"]->prepare($claimQuery);
            $SQL->execute(array($profile, $this->id));

            $verificationQuery = "SELECT profile FROM users WHERE users.id = ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($this->id));

            $result = $SQL->fetch();

            if ($result["profile"] != $profile) {
                throw new Exception("Perfil Reivindicado Não Encontrado");
            } else {
                $this->profileID = $result["profile"];
            }

        }

        function releaseProfile () {
            if (!$this->profileID) throw new Exception("Usuário Não Possui Perfil Reivindicado.");

            $releaseQuery = "UPDATE users SET profile = NULL WHERE users.id = ?";
            $SQL = $GLOBALS["conn"]->prepare($releaseQuery);
            $SQL->execute(array($this->id));

            $verificationQuery = "SELECT profile FROM users WHERE users.id = ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($this->id));

            $result = $SQL->fetch();

            if ($result["profile"] !== null) {
                throw new Exception("Perfil Reivindicado Não Encontrado");
            } else {
                $this->profileID = $result["profile"];
            }

        }

        function activateProfile(bool $activationFlag) {
            if (!isset($this->profileID)) {    
                throw new Exception("Nenhum Perfil Reivindicado.");
            }

            if ($activationFlag === false) {
                $activationQuery = "UPDATE cooperative_partners SET active = 0 WHERE id = ?";
            } else {
                $activationQuery = "UPDATE cooperative_partners SET active = 1 WHERE id = ?";
            }
            

            $SQL = $GLOBALS["conn"]->prepare($activationQuery);
            $SQL->execute(array($this->profileID));

            $verificationQuery = "SELECT active FROM cooperative_partners WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($activationQuery);
            $SQL->execute(array($this->profileID));

            $result = $SQL->fetchColumn();

            if ($activationFlag === false) {
                if ($result === "0" || $result === 0) {
                    throw new Exception("Erro Ao Desativar o Perfil.");
                } 
            } else {
                if ($result === "1" || $result === 1) {
                    throw new Exception("Erro Ao Ativar o Perfil.");
                } 
            }
            
        }

        function updateProfilePicture(string $imagePath) {
            if (!isset($this->profileID)) {    
                throw new Exception("Nenhum Perfil Reivindicado.");
            }
            
            $updateQuery = "UPDATE cooperative_partners SET imagem = ? WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($updateQuery);
            $SQL->execute(array($imagePath, $this->profileID));

            $verificationQuery = "SELECT imagem FROM cooperative_partners WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($this->profileID));

            $result = $SQL->fetchColumn();

            if ($result != $imagePath) {
                throw new Exception("Erro ao Salvar Imagem.");
            } else {
                if (!isset($this->profile)) $this->hydrateProfile();
                $this->profile->image_setter($result);
            }
            
        }

        function __toString() {
            return $this->username_getter();
        }

        public static function countUsers() {
            $countQuery = "SELECT COUNT(*) FROM users";
            $SQL = $GLOBALS["conn"]->query($countQuery);
            return $SQL->fetchColumn();
        }

        public static function fetchUsers($limit = null, $offset = 0) {
            
            if (isset($limit)) {                
                $fetchQuery = "SELECT A.id, A.username, A.active, A.admin, A.profile, B.nome FROM users AS A LEFT JOIN cooperative_partners AS B ON A.profile = B.id ORDER BY `active` DESC, `admin` DESC, `username` ASC LIMIT ?, ?";
                $GLOBALS["conn"]->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute(array($offset, $limit));
                
            } else {
                $fetchQuery = "SELECT A.id, A.username, A.active, A.admin, A.profile, B.nome FROM users AS A LEFT JOIN cooperative_partners AS B ON A.profile = B.id ORDER BY `active` DESC, `admin` DESC, `username` ASC";
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute();
            }
            
            return $SQL->fetchAll();
        }

        public static function fetchUnclaimed() {
            $fetchQuery = "SELECT A.id, A.username FROM users as A LEFT JOIN cooperative_partners as B ON B.id = A.profile WHERE A.profile IS NULL";
            $SQL = $GLOBALS["conn"]->query($fetchQuery);
            return $SQL->fetchAll();
        }

        public static function findUsers($searchTerm, $limit = null, $offset = 0) {
            
            if (isset($limit)) {                
                $fetchQuery = "SELECT A.id, A.username, A.active, A.admin, A.profile, B.nome FROM users AS A LEFT JOIN cooperative_partners AS B ON A.profile = B.id WHERE A.username LIKE ? or B.nome LIKE ? ORDER BY `active` DESC, `admin` DESC, `username` ASC LIMIT ?, ?";
                $GLOBALS["conn"]->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute(array($searchTerm."%", $searchTerm."%", $offset, $limit));
                
            } else {
                $fetchQuery = "SELECT A.id, A.username, A.active, A.admin, A.profile, B.nome FROM users AS A LEFT JOIN cooperative_partners AS B ON A.profile = B.id WHERE A.username LIKE ? or B.nome LIKE ? ORDER BY `active` DESC, `admin` DESC, `username` ASC";
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute(array($searchTerm."%", $searchTerm."%"));
            }
            
            return $SQL->fetchAll();
        }

        public static function createUser($username, $password, $active = 0, $admin = 0) {

            $preCheckQuery = "SELECT COUNT(*) FROM users WHERE username = ?";
            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute(array($username));
            if (intval($SQL->fetchColumn()) > 0) throw new Exception("Nome de usuário em uso !");

            $createQuery = "INSERT INTO users (username, password, active, admin) VALUES (?, ?, ?, ?)";
            $SQL = $GLOBALS["conn"]->prepare($createQuery);
            $SQL->execute(array($username, password_hash($password, PASSWORD_DEFAULT), $active, $admin));
            
            return $SQL->fetch();

        }

        public static function bulkCreateUsers($userArray) {

            $usernames = array();
            foreach ($userArray as $index => $user) {
                array_push($usernames, $user["username"]);
                $user["username"] = ":".$user["username"];
            }

            $uniqueUsernames = array_unique($usernames);
            if (count($usernames) != count($uniqueUsernames)) {
                return array("success" => false, "reprovedUsers" => array_diff_assoc($usernames, $uniqueUsernames));
            }

            $preCheckQuery = "SELECT id, username FROM users WHERE username in (".(
                implode(", ", array_fill(0, count($usernames), "?"))
            ).")";

            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute($usernames);
            $preCheckResult = $SQL->fetchAll();
            
            if (count($preCheckResult) > 0) {
                return array("success" => false, "reprovedUsers" => $preCheckResult);
            }

            $insertQueryRoot = "INSERT INTO users (username, password, active, admin) VALUES ";
            $insertQueryPlaceholder = implode(", ", array_fill(0, count($userArray), "(?, ?, ?, ?)"));
            $insertQueryFinal = $insertQueryRoot.$insertQueryPlaceholder;

            $flatUserArray = array();
            foreach ($userArray as $i => $j) {
                foreach ($j as $x => $y) {
                    array_push($flatUserArray, $y);
                }
            }

            $SQL = $GLOBALS["conn"]->prepare($insertQueryFinal);
            $SQL->execute($flatUserArray);

            return array("success" => true);
        }


        public static function removeUser($id) {

            $preCheckQuery = "SELECT COUNT(*) FROM users WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute(array($id));
            if (intval($SQL->fetchColumn()) !== 1) throw new Exception("Usuário Não encontrado !");

            $removeQuery = "DELETE FROM users WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($removeQuery);
            $SQL->execute(array($id));
        }

        public static function toggleUserState($id, $status) {
            $preCheckQuery = "SELECT COUNT(*) FROM users WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute(array($id));
            if (intval($SQL->fetchColumn()) !== 1) throw new Exception("Usuário Não encontrado !");

            if ($status === "activate") {
                $statusQuery = "UPDATE users SET active = 1 WHERE id = ?";
            } else if ($status === "deactivate") {
                $statusQuery = "UPDATE users SET active = 0 WHERE id = ?";
            } else {
                throw new Exception("Unknown User State Requested !");
            }

            $SQL = $GLOBALS["conn"]->prepare($statusQuery);
            $SQL->execute(array($id));
        }

        public static function toggleUserAdminState($id, $status) {
            $preCheckQuery = "SELECT COUNT(*) FROM users WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute(array($id));
            if (intval($SQL->fetchColumn()) !== 1) throw new Exception("Usuário Não encontrado !");

            if ($status === "activate") {
                $statusQuery = "UPDATE users SET admin = 1 WHERE id = ?";
            } else if ($status === "deactivate") {
                $statusQuery = "UPDATE users SET admin = 0 WHERE id = ?";
            } else {
                throw new Exception("Unknown User Admin State Requested !");
            }

            $SQL = $GLOBALS["conn"]->prepare($statusQuery);
            $SQL->execute(array($id));
        }

    }
?>