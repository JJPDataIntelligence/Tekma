<?php require_once(__DIR__."/../connection.php"); ?>
<?php require_once(__DIR__."/../model/Member.php"); ?>
<?php 

    $conn = makeConnection();

    class BusinessUnit {
        const QUERIES = [
            'CORE' => 'SELECT name, head as headID, parent_id as parentID, description_en, description_pt, short_description_en, short_description_pt FROM business_units WHERE id = ?',
            'MEMBERS' => 'SELECT cooperative_partner as member FROM bu_members WHERE business_unit = ?',
            'CHILDREN' => 'SELECT id as child FROM business_units WHERE parent_id = ?'
        ];

        private $name;
        private $head;
        private $description_en;
        private $description_pt;
        private $short_description_en;
        private $short_description_pt;
        private $children = array();
        private $parent;
        private $members = array();
        
        private $headID;
        private $parentID;
        
        private $id;
        private $lang;

        function __construct($id, $language = 'pt', $fetchChildren = FALSE, $fetchParent = FALSE, $fetchHead = TRUE, $fetchMembers = TRUE) {
            $this->id = $id;
            $this->lang = $language;

            $statements = [
                'CORE' => $GLOBALS['conn']->prepare(self::QUERIES['CORE']),
                'MEMBERS' => $GLOBALS['conn']->prepare(self::QUERIES['MEMBERS']),
                'CHILDREN' => $GLOBALS['conn']->prepare(self::QUERIES['CHILDREN'])
            ];
         
            // CORE
            $statements['CORE']->execute(array($this->id));
            $statements['CORE']->bindColumn('name', $this->name);
            $statements['CORE']->bindColumn('description_pt', $this->description_pt);
            $statements['CORE']->bindColumn('description_en', $this->description_en);
            $statements['CORE']->bindColumn('short_description_pt', $this->short_description_pt);
            $statements['CORE']->bindColumn('short_description_en', $this->short_description_en);
            $statements['CORE']->bindColumn('headID', $this->headID);
            $statements['CORE']->bindColumn('parentID', $this->parentID);

            $statements['CORE']->fetchAll(PDO::FETCH_BOUND);

            // HEAD
            if ($this->headID && $fetchHead) {
                $this->head = new Member($this->headID, $this->lang);
            }
            
            // MEMBERS
            if ($fetchMembers) {
                $statements['MEMBERS']->execute(array($this->id));
                $members = $statements['MEMBERS']->fetchAll(PDO::FETCH_ASSOC);
                foreach($members as $key => $id) {
                    array_push($this->members, new Member($id['member'], $this->lang));
                }
            }

            // CHILDREN
            if ($fetchChildren) {
                $statements['CHILDREN']->execute(array($this->id));
                $children = $statements['CHILDREN']->fetchAll(PDO::FETCH_ASSOC);
                
                foreach($children as $key => $id) {
                    array_push($this->children, new BusinessUnit($id['child'], $this->lang, TRUE, FALSE, TRUE, TRUE));
                }
            }

            // PARENT
            if ($this->parentID && $fetchParent) {
                $this->parent = new BusinessUnit($this->parentID, $this->lang, FALSE, TRUE, TRUE, TRUE);
            }
        }

        function id_getter() {
            return $this->id;
        }

        function name_getter() {
            return $this->name;
        }

        function portugueseDescription_getter() {
            return $this->description_pt;
        }

        function englishDescription_getter() {
            return $this->description_en;
        }

        function portugueseShortDescription_getter() {
            return $this->short_description_pt;
        }

        function englishShortDescription_getter() {
            return $this->short_description_en;
        }

        function description_getter() {
            $description = '';

            if ($this->lang === 'pt') {
                $description = $this->description_pt;
            } else {
                $description = $this->description_en;
            }

            if ($description) {
                return $description;
            } else {
                return ($this->lang === 'pt' ? "Descrição Não Encontrada" : "Description Not Found");
            }
        }

        function short_description_getter() {
            $description = '';

            if ($this->lang === 'pt') {
                $description = $this->short_description_pt;
            } else {
                $description = $this->short_description_en;
            }

            if ($description) {
                return $description;
            } else {
                return ($this->lang === 'pt' ? "Descrição Não Encontrada" : "Description Not Found");
            }
        }

        function head_getter() {
            return $this->head;
        }

        function parent_getter() {
            return $this->parent; 
        }

        function children_getter() {
            return $this->children; 
        }

        function members_getter() {
            return $this->members;
        }

        function hasParent() {
            return isset($this->parentID);
        }

        function hasChildren() {
            $verificationQuery = "SELECT COUNT(*) FROM business_units WHERE parent_id = ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($this->id));
            return $SQL->fetchColumn() > 0;
        }

        function fetchChildren() {
            $verificationQuery = "SELECT id, name FROM business_units WHERE parent_id = ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($this->id));
            return $SQL->fetchAll();
        }

        function fetchOrphanBUS() {
            $verificationQuery = "SELECT id, name FROM business_units WHERE parent_id IS NULL and id <> ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($this->id));
            return $SQL->fetchAll();
        }

        function fetchNonMembers() {
            $fetchQuery = "SELECT DISTINCT A.id, A.nome FROM cooperative_partners AS A LEFT JOIN bu_members AS B ON A.id = B.cooperative_partner WHERE B.cooperative_partner IS NULL OR B.business_unit <> ?";
            $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
            $SQL->execute(array($this->id));
            return $SQL->fetchAll();
        }

        function updateName($name){
            $updateQuery = "UPDATE business_units SET `name` = ? WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($updateQuery);
            $SQL->execute(array($name, $this->id));
            return $SQL->fetchAll();
        }

        function updatePortugueseDescription($description_pt){
            $updateQuery = "UPDATE business_units SET description_pt = ? WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($updateQuery);
            $SQL->execute(array($description_pt, $this->id));
            return $SQL->fetchAll();
        }

        function updateEnglishDescription($description_en){
            $updateQuery = "UPDATE business_units SET description_en = ? WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($updateQuery);
            $SQL->execute(array($description_en, $this->id));
            return $SQL->fetchAll();
        }

        function updatePortugueseShortDescription($short_description_pt){
            $updateQuery = "UPDATE business_units SET short_description_pt = ? WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($updateQuery);
            $SQL->execute(array($short_description_pt, $this->id));
            return $SQL->fetchAll();
        }

        function updateEnglishShortDescription($short_description_en){
            $updateQuery = "UPDATE business_units SET short_description_en = ? WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($updateQuery);
            $SQL->execute(array($short_description_en, $this->id));
            return $SQL->fetchAll();
        }

        // Development Helpers
        function print_branch($depth = 1) {
            if($depth == 1) echo 'Branch Started From "'.$this->name_getter().'" (depth = '.$depth.').</br>';
            echo str_repeat('-', $depth).$this->name_getter().'<br/>';
            foreach($this->children as $i => $j) {        
                $j->print_branch($depth + 1);
            }
        }

        function toJSON($encode = TRUE) {
            
            $response = array();
            $response['type'] = "BUSINESS-UNIT";
            $response['name'] = $this->name_getter();
            if ($this->head_getter()) $response['head'] = $this->head_getter()->toJSON(FALSE);
            if ($this->parent_getter()) $response['parent'] = $this->parent_getter()->toJSON(FALSE);
            
            $response['description'] = $this->description_getter();
            $response['shortDescription'] = $this->short_description_getter();

            $response['children'] = array();
            if (count($this->children_getter()) > 0) {
                foreach ($this->children_getter() as $i => $child) {
                    array_push($response['children'], $child->toJSON(FALSE));
                }
            }
            
            $response['members'] = array();
            if (count($this->members_getter()) > 0) {
                foreach ($this->members_getter() as $i => $member) {
                    array_push($response['members'], $member->toJSON(FALSE));
                }
            }

            if ($encode) {
                return json_encode($response);
            } else {
                return $response;
            }
        }

        function __toString() {
            return $this->name_getter();
        }

        public static function countBUS() {
            $countQuery = "SELECT COUNT(*) FROM business_units";
            $SQL = $GLOBALS["conn"]->query($countQuery);
            return $SQL->fetchColumn();
        }

        public static function fetchBUS($limit = null, $offset = 0) {
            
            if (isset($limit)) {      
                $fetchQuery = "SELECT A.id, A.name, A.head as headID, C.nome as head, B.name as parent, A.parent_id FROM business_units AS A LEFT JOIN business_units AS B ON A.parent_id = B.id LEFT JOIN cooperative_partners AS C ON A.head = C.id ORDER BY A.`parent_id` ASC, A.`name` ASC LIMIT ?, ?";
                $GLOBALS["conn"]->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute(array($offset, $limit));
                
            } else {
                $fetchQuery = "SELECT A.id, A.name, A.head as headID, C.nome as head, B.name as parent, A.parent_id FROM business_units AS A LEFT JOIN business_units AS B ON A.parent_id = B.id LEFT JOIN cooperative_partners AS C ON A.head = C.id ORDER BY A.`parent_id` ASC, A.`name` ASC";
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute();
            }
            
            return $SQL->fetchAll();
        }

        public static function findBUS($searchTerm, $limit = null, $offset = 0) {
            
            if (isset($limit)) {
                $fetchQuery = "SELECT A.id, A.name, A.head as headID, C.nome as head, B.name as parent, A.parent_id FROM business_units AS A LEFT JOIN business_units AS B ON A.parent_id = B.id LEFT JOIN cooperative_partners AS C ON A.head = C.id WHERE A.name LIKE ? or C.nome LIKE ? ORDER BY A.`parent_id` ASC, A.`name` ASC LIMIT ?, ?";
                $GLOBALS["conn"]->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute(array($searchTerm."%", $searchTerm."%", $offset, $limit));
                
            } else {
                $fetchQuery = "SELECT A.id, A.name, A.head as headID, C.nome as head, B.name as parent, A.parent_id FROM business_units AS A LEFT JOIN business_units AS B ON A.parent_id = B.id LEFT JOIN cooperative_partners AS C ON A.head = C.id WHERE A.name LIKE ? or C.nome LIKE ? ORDER BY A.`parent_id` ASC, A.`name` ASC";
                $SQL = $GLOBALS["conn"]->prepare($fetchQuery);
                $SQL->execute(array($searchTerm."%", $searchTerm."%"));
            }
            
            return $SQL->fetchAll();
        }

        public static function createBU($name) {
            
            $preCheckQuery = "SELECT COUNT(*) FROM business_units WHERE `name` = ?";
            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute(array($name));
            $preCheckResult = $SQL->fetchColumn();

            if (intval($preCheckResult) > 0) throw new Exception("Encontramos uma Unidade de Negócio que já utiliza este nome. Unidades de Negócio devem ter nomes únicos.");

            $createQuery = "INSERT INTO business_units (`name`) VALUES (?)";
            $SQL = $GLOBALS["conn"]->prepare($createQuery);
            $SQL->execute(array($name));

            $verificationQuery = "SELECT id, name FROM business_units WHERE name = ?";
            $SQL = $GLOBALS["conn"]->prepare($verificationQuery);
            $SQL->execute(array($name));
            $validationResult = $SQL->fetch();

            if (!isset($validationResult) || !isset($validationResult["id"])) throw new Exception("Erro ao criar Unidade de Negócio");

            return $validationResult["id"];

        }

        public static function bulkCreateBUS($busArray) {

            $fullNames = array();
            foreach ($busArray as $index => $bu) {
                array_push($fullNames, $bu["name"]);
                $bu["name"] = ":".$bu["name"];
            }

            $uniqueFullNames = array_unique($fullNames);
            if (count($fullNames) != count($uniqueFullNames)) {
                return array("success" => false, "reprovedBUS" => array_diff_assoc($fullNames, $uniqueFullNames));
            }

            $preCheckQuery = "SELECT id, `name` FROM business_units WHERE `name` in (".(
                implode(", ", array_fill(0, count($uniqueFullNames), "?"))
            ).")";

            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute($fullNames);
            $preCheckResult = $SQL->fetchAll();
            
            if (count($preCheckResult) > 0) {
                return array("success" => false, "reprovedBUS" => $preCheckResult);
            }

            $insertQueryRoot = "INSERT INTO business_units (`name`, description_pt, description_en) VALUES ";
            $insertQueryPlaceholder = implode(", ", array_fill(0, count($busArray), "(?, ?, ?)"));
            $insertQueryFinal = $insertQueryRoot.$insertQueryPlaceholder;

            $flatBUSArray = array();
            foreach ($busArray as $i => $j) {
                foreach ($j as $x => $y) {
                    array_push($flatBUSArray, $y);
                }
            }

            $SQL = $GLOBALS["conn"]->prepare($insertQueryFinal);
            $SQL->execute($flatBUSArray);

            return array("success" => true);
        }

        public static function removeBU($id) {

            $preCheckQuery = "SELECT COUNT(*) FROM business_units WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($preCheckQuery);
            $SQL->execute(array($id));
            if (intval($SQL->fetchColumn()) !== 1) throw new Exception("Unidade de Negócio Não encontrado !");

            $removeQuery = "DELETE FROM business_units WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($removeQuery);
            $SQL->execute(array($id));
        }


        public static function associateBUS($parentID, $children) {
            $disassociationQuery = "UPDATE business_units SET parent_id = NULL WHERE parent_id = ?";
            $SQL = $GLOBALS["conn"]->prepare($disassociationQuery);
            $SQL->execute(array($parentID));

            $placeholders = "(".substr(str_repeat("?, ", count($children)), 0, -2).")";

            $associationQuery = "UPDATE business_units SET parent_id = ? WHERE id IN ".$placeholders;
            $SQL = $GLOBALS["conn"]->prepare($associationQuery);
            $SQL->execute(array_merge(array($parentID), $children));
        }

        public static function associateMembers($buID, $members) {
            $disassociationQuery = "DELETE FROM bu_members WHERE business_unit = ?";
            $SQL = $GLOBALS["conn"]->prepare($disassociationQuery);
            $SQL->execute(array($buID));

            if (isset($members) && $members != null) {
                $placeholders = "(".substr(str_repeat("?, ", count($members)), 0, -2).")";

                $associationQueryRoot = "INSERT INTO bu_members(business_unit, cooperative_partner) VALUES ";
                $associationQueryPlaceholder = implode(", ", array_fill(0, count($members), "(?, ?)"));
                $associationQueryFinal = $associationQueryRoot.$associationQueryPlaceholder;
    
                $associationParams = array();
                foreach($members as $key => $member) {
                    array_push($associationParams, $buID, $member);
                }
    
                $SQL = $GLOBALS["conn"]->prepare($associationQueryFinal);
                $SQL->execute($associationParams);
            }
        }

        public static function associateHead($buID, $head) {
            $associationQuery = "UPDATE business_units SET head = ? WHERE id = ?";
            $SQL = $GLOBALS["conn"]->prepare($associationQuery);
            $SQL->execute(array($head, $buID));
        }


    }
?>