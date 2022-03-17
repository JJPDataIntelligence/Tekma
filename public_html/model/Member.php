<?php require_once(__DIR__."/../connection.php");?>
<?php 

    $conn = makeConnection();

    class Member {
        const QUERY = 'SELECT id, nome as name, apelido as nickname, title, title_en, email, imagem as image, descricao_pt as portugueseDescription, descricao_en as englishDescription, short_description_pt as portugueseShortDescription, short_description_en as englishShortDescription, linkedin, active FROM cooperative_partners WHERE id = ?';
        
        private $name;
        private $nickname;
        private $title;
        private $englishTitle;
        private $email;
        private $image;
        private $portugueseDescription;
        private $englishDescription;
        private $portugueseShortDescription;
        private $englishShortDescription;
        private $linkedin;
        private $active;


        private $id;
        private $language;

        function __construct($id, $language = 'pt') {
            $this->id = $id;
            $this->language = $language;

            $SQL = $GLOBALS["conn"]->prepare(self::QUERY);
            $SQL->execute(array($this->id));

            $SQL->bindColumn('name', $this->name);
            $SQL->bindColumn('nickname', $this->nickname);
            $SQL->bindColumn('title', $this->title);
            $SQL->bindColumn('title_en', $this->englishTitle);
            $SQL->bindColumn('email', $this->email);
            $SQL->bindColumn('image', $this->image);
            $SQL->bindColumn('portugueseDescription', $this->portugueseDescription);
            $SQL->bindColumn('englishDescription', $this->englishDescription);
            $SQL->bindColumn('portugueseShortDescription', $this->portugueseShortDescription);
            $SQL->bindColumn('englishShortDescription', $this->englishShortDescription);
            $SQL->bindColumn('linkedin', $this->linkedin);
            $SQL->bindColumn('active', $this->active);

            $result = $SQL->fetchAll(PDO::FETCH_BOUND);
            
            if (count($result) === 0) {
                throw new Exception("Perfil Não Encontrado !");
            }
            
        }

        function id_getter() {
            return $this->id;
        }

        function name_getter() {
            return $this->name;
        }

        function nickname_getter() {
            return $this->nickname;
        }

        function portugueseTitle_getter() {
            return $this->title;
        }

        function englishTitle_getter() {
            return $this->englishTitle;
        }

        function email_getter() {
            return $this->email;
        }

        function image_getter() {
            return $this->image;
        }

        function image_setter(string $image) {
            return $this->image = $image;
        }

        function portugueseDescription_getter() {
            return $this->portugueseDescription;
        }

        function portugueseShortDescription_getter() {
            return $this->portugueseShortDescription;
        }

        function englishDescription_getter() {
            return $this->englishDescription;
        }

        function englishShortDescription_getter() {
            return $this->englishShortDescription;
        }

        function description_getter() {
            switch($this->language) {
                case 'pt': 
                    return $this->portugueseDescription;
                case 'en':
                    return $this->englishDescription;
                default:
                    throw new Exception("Language Not Available.");
                    die();
            }            
        }

        function shortDescription_getter() {
            switch($this->language) {
                case 'pt': 
                    return $this->portugueseShortDescription;
                case 'en':
                    return $this->englishShortDescription;
                default:
                    throw new Exception("Language Not Available.");
                    die();
            }            
        }

        function title_getter() {
            switch($this->language) {
                case 'pt': 
                    return $this->title;
                case 'en':
                    return $this->englishTitle;
                default:
                    throw new Exception("Language Not Available.");
                    die();
            }            
        }

        function linkedin_getter() {
            return $this->linkedin;
        }

        function active_getter() {
            return $this->active;
        }

        function toJSON($encode = TRUE) {
            
            $response = array();
            $response['type'] = "MEMBER";
            $response['name'] = $this->name_getter();
            $response['nickname'] = $this->nickname_getter();
            $response['title'] = $this->title_getter();
            $response['image'] = $this->image_getter();
            $response['email'] = $this->email_getter();
            $response['linkedin'] = $this->linkedin_getter();
            $response['description'] = $this->description_getter();
            $response['shortDescription'] = $this->shortDescription_getter();
            
            if ($encode) {
                return json_encode($response);
            } else {
                return $response;
            }
        }

        function isActive () {
            return $this->active === "1";
        }

        function getOwner () {
            $verificationQuery = "SELECT COUNT(*) FROM users WHERE profile = ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($this->id));
            if ($SQL->fetchColumn() <= 0) return false;

            $fetchQuery = "SELECT username FROM users WHERE profile = ?";
            $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
            $SQL->execute(array($this->id));
            return new User($SQL->fetchColumn());
        }

        public static function countMembers() {
            $countQuery = "SELECT COUNT(*) FROM cooperative_partners";
            $SQL = $GLOBALS["conn"]->query($countQuery);
            return $SQL->fetchColumn();
        }

        public static function fetchUnclaimed() {
            $fetchQuery = "SELECT A.id, A.nome as name FROM cooperative_partners as A LEFT JOIN users as B ON A.id = B.profile WHERE B.profile IS NULL";
            $SQL = $GLOBALS["conn"]->query($fetchQuery);
            return $SQL->fetchAll();
        }

        public static function fetchClaimed() {
            $fetchQuery = "SELECT A.id, A.nome as name FROM cooperative_partners as A LEFT JOIN users as B ON A.id = B.profile WHERE B.profile IS NOT NULL";
            $SQL = $GLOBALS["conn"]->query($fetchQuery);
            return $SQL->fetchAll();
        }

        public static function fetchMembers($limit = null, $offset = 0) {
            if (isset($limit)) {
                
                $fetchQuery = "SELECT A.id, A.nome, A.apelido, A.email, A.active, B.username FROM cooperative_partners AS A LEFT JOIN users AS B ON A.id = B.profile ORDER BY `active` DESC, `apelido` ASC, `nome` ASC LIMIT ?, ?";
                $GLOBALS["conn"]->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute(array($offset, $limit));
                
            } else {
                $fetchQuery = "SELECT A.id, A.nome, A.apelido, A.email, A.active, B.username FROM cooperative_partners AS A LEFT JOIN users AS B ON A.id = B.profile ORDER BY `active` DESC, `apelido` ASC, `nome` ASC";
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute();
            }
            
            return $SQL->fetchAll();
        }

        public static function findMembers($searchTerm, $limit = null, $offset = 0) {
            
            if (isset($limit)) {                
                $fetchQuery = "SELECT A.id, A.nome, A.apelido, A.email, A.active, B.username FROM cooperative_partners AS A LEFT JOIN users AS B ON A.id = B.profile WHERE A.nome LIKE ? or A.apelido LIKE ? ORDER BY `active` DESC, `apelido` ASC, `nome` ASC LIMIT ?, ?";
                $GLOBALS["conn"]->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute(array($searchTerm."%", $searchTerm."%", $offset, $limit));
                
            } else {
                $fetchQuery = "SELECT A.id, A.nome, A.apelido, A.email, A.active, B.username FROM cooperative_partners AS A LEFT JOIN users AS B ON A.id = B.profile WHERE A.nome LIKE ? or A.apelido LIKE ? ORDER BY `active` DESC, `apelido` ASC, `nome` ASC";
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute(array($searchTerm."%", $searchTerm."%"));
            }
            
            return $SQL->fetchAll();
        }

        public static function createMember($name, $nickname, $title, $image, $linkedin, $email, $description_pt, $description_en, $shortDescription_pt, $shortDescription_en, $title_en) {
            
            $preCheckQuery = "SELECT COUNT(*) FROM cooperative_partners WHERE nome = ?";
            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute(array($name));
            $preCheckResult = $SQL->fetchColumn();

            if (intval($preCheckResult) > 0) throw new Exception("Encontramos um perfil que já utiliza este nome. Se possível, reivindique este perfil ou, caso ele não esteja disponível, entre em contato com um administrador.");

            $createQuery = "INSERT INTO cooperative_partners (nome, apelido, title, imagem, linkedin, email, descricao_pt, descricao_en, short_description_pt, short_description_en, title_en) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $SQL = $GLOBALS["conn"]->prepare($createQuery);
            $SQL->execute(array($name, $nickname, $title, $image, $linkedin, $email, $description_pt, $description_en, $shortDescription_pt, $shortDescription_en, $title_en));

            $verificationQuery = "SELECT id, nome FROM cooperative_partners WHERE nome = ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($name));
            $validationResult = $SQL->fetch();

            if (!isset($validationResult) || !isset($validationResult["id"])) throw new Exception("Erro ao criar Perfil");

            return $validationResult["id"];

        }

        public static function bulkCreateMembers($profileArray) {

            $fullNames = array();
            foreach ($profileArray as $index => $profile) {
                array_push($fullNames, $profile["nome"]);
                $profile["nome"] = ":".$profile["nome"];
            }

            $uniqueFullNames = array_unique($fullNames);
            if (count($fullNames) != count($uniqueFullNames)) {
                return array("success" => false, "reprovedProfiles" => array_diff_assoc($fullNames, $uniqueFullNames));
            }

            $preCheckQuery = "SELECT id, nome FROM cooperative_partners WHERE nome in (".(
                implode(", ", array_fill(0, count($uniqueFullNames), "?"))
            ).")";

            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute($fullNames);
            $preCheckResult = $SQL->fetchAll();
            
            if (count($preCheckResult) > 0) {
                return array("success" => false, "reprovedProfiles" => $preCheckResult);
            }

            $insertQueryRoot = "INSERT INTO cooperative_partners (nome, apelido, title, email, linkedin, short_description_pt, short_description_en, descricao_pt, descricao_en, title_en) VALUES ";
            $insertQueryPlaceholder = implode(", ", array_fill(0, count($profileArray), "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"));
            $insertQueryFinal = $insertQueryRoot.$insertQueryPlaceholder;

            $flatProfileArray = array();
            foreach ($profileArray as $i => $j) {
                foreach ($j as $x => $y) {
                    array_push($flatProfileArray, $y);
                }
            }

            $SQL = $GLOBALS["conn"]->prepare($insertQueryFinal);
            $SQL->execute($flatProfileArray);
            

            return array("success" => true);
        }

        public static function updateProfilePicture($profileID, $imagePath) {            
            $updateQuery = "UPDATE cooperative_partners SET imagem = ? WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($updateQuery);
            $SQL->execute(array($imagePath, $profileID));

            $verificationQuery = "SELECT imagem FROM cooperative_partners WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($profileID));

            $result = $SQL->fetchColumn();

            if ($result != $imagePath) {
                throw new Exception("Erro ao Salvar Imagem.");
            } else {
                $profile = new Member($profileID);
                $profile->image_setter($result);
            }
            
        }

        public static function updateMember($profileID, $updateArray) {

            $updateQuery = "UPDATE cooperative_partners SET ";

            $validFields = [
                "name" => "nome",
                "nickname" => "apelido",
                "title" => "title",
                "email" => "email",
                "linkedin" => "linkedin",
                "portugueseDescription" => "descricao_pt",
                "englishDescription" => "descricao_en",
                "portugueseShortDescription" => "short_description_pt",
                "englishShortDescription" => "short_description_en",
                "title_en" => "title_en",
            ];

            $executeArray = [];

            foreach ($updateArray as $field => $value) {
                if (in_array($field, array_keys($validFields))) {
                    $updateQuery = $updateQuery.$validFields[$field]." = :".$field.", ";
                    $executeArray = array_merge($executeArray, [":".$field => $value]);
                } else {
                    unset($updateArray[$field]);
                }
            }

            $updateQuery = mb_substr($updateQuery, 0, -2)." WHERE id = :id";
            $SQL = $GLOBALS["conn"]->prepare($updateQuery);
            $SQL->execute(array_merge($executeArray, [":id" => $profileID]));

            $verificationQuery = "SELECT id FROM cooperative_partners WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($profileID));
            $result = $SQL->fetchColumn();

            if (!isset($result)) throw new Exception("Erro ao Atualizar Perfil");

            return $result;
        }

        public static function toggleMemberState($id, $status) {
            $preCheckQuery = "SELECT COUNT(*) FROM cooperative_partners WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute(array($id));
            if (intval($SQL->fetchColumn()) !== 1) throw new Exception("Perfil Não encontrado !");

            if ($status === "activate") {
                $statusQuery = "UPDATE cooperative_partners SET active = 1 WHERE id = ?";
            } else if ($status === "deactivate") {
                $statusQuery = "UPDATE cooperative_partners SET active = 0 WHERE id = ?";
            } else {
                throw new Exception("Unknown Profile State Requested !");
            }

            $SQL = $GLOBALS["conn"]->prepare($statusQuery);
            $SQL->execute(array($id));
        }

        public static function removeMember($id) {

            $preCheckQuery = "SELECT COUNT(*) FROM cooperative_partners WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute(array($id));
            if (intval($SQL->fetchColumn()) !== 1) throw new Exception("Perfil Não encontrado !");

            $removeQuery = "DELETE FROM cooperative_partners WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($removeQuery);
            $SQL->execute(array($id));
        }

        function __toString() {
            return $this->name_getter();
        }

    }
?>