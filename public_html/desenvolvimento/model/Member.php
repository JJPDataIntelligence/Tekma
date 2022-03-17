<?php require_once(__DIR__."/../conn.php");?>
<?php 

    $conn = makeConnection();

    class Member {
        const QUERY = 'SELECT id, nome as name, apelido as nickname, title, email, imagem as image, descricao_pt as portugueseDescription, descricao_en as englishDescription, short_description_pt as portugueseShortDescription, short_description_en as englishShortDescription, linkedin FROM cooperative_partners WHERE id = ?';
        
        private $name;
        private $nickname;
        private $title;
        private $email;
        private $image;
        private $portugueseDescription;
        private $englishDescription;
        private $portugueseShortDescription;
        private $englishShortDescription;
        private $linkedin;


        private $id;
        private $language;

        function __construct($id, $language) {
            $this->id = $id;
            $this->language = $language;

            $SQL = $GLOBALS["conn"]->prepare(self::QUERY);
            $SQL->execute(array($this->id));

            $SQL->bindColumn('name', $this->name);
            $SQL->bindColumn('nickname', $this->nickname);
            $SQL->bindColumn('title', $this->title);
            $SQL->bindColumn('email', $this->email);
            $SQL->bindColumn('image', $this->image);
            $SQL->bindColumn('portugueseDescription', $this->portugueseDescription);
            $SQL->bindColumn('englishDescription', $this->englishDescription);
            $SQL->bindColumn('portugueseShortDescription', $this->portugueseShortDescription);
            $SQL->bindColumn('englishShortDescription', $this->englishShortDescription);
            $SQL->bindColumn('linkedin', $this->linkedin);

            $result = $SQL->fetchAll(PDO::FETCH_BOUND);            
        }

        function name_getter() {
            return $this->name;
        }

        function nickname_getter() {
            return $this->nickname;
        }

        function title_getter() {
            return $this->title;
        }

        function email_getter() {
            return $this->email;
        }

        function image_getter() {
            return $this->image;
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

        function linkedin_getter() {
            return $this->linkedin;
        }

        function toJSON($encode = TRUE) {
            
            $response = [];
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

        function __toString() {
            return $this->name_getter();
        }

    }
?>