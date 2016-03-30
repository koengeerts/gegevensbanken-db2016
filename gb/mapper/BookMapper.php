<?php
namespace gb\mapper;

$EG_DISABLE_INCLUDES=true;
require_once( "gb/mapper/Mapper.php" );
require_once( "gb/domain/Book.php" );


class BookMapper extends Mapper {

    function __construct() {
        parent::__construct();
        $this->selectStmt = "SELECT a.* from book a where a.uri = ?";
        $this->selectAllStmt = "SELECT a.* from book a";
    }

    function getCollection( array $raw ) {

        $booksCollection = array();
        foreach($raw as $row) {
            array_push($booksCollection, $this->doCreateObject($row));
        }

        return $booksCollection;
    }

    protected function doCreateObject( array $array ) {

        $obj = null;
        if (count($array) > 0) {
            $obj = new \gb\domain\Book( $array['uri'] );

            $obj->setUri($array['uri']);
            $obj->setName($array['name']);
            $obj->setDescription($array['description']);
            $obj->setOriginalLanguage($array['original_language']);
            $obj->setFirstPublicationDate($array['first_publication_date']);
            $obj->setNbAwards($this->getNbAwards($obj->getUri()));
        }

        return $obj;
    }

    protected function doInsert( \gb\domain\DomainObject $object ) {
        /*$values = array( $object->getName() );
        $this->insertStmt->execute( $values );
        $id = self::$PDO->lastInsertId();
        $object->setId( $id );*/
    }

    function update( \gb\domain\DomainObject $object ) {
        //$values = array( $object->getName(), $object->getId(), $object->getId() ); 
        //$this->updateStmt->execute( $values );
    }

    function selectStmt() {
        return $this->selectStmt;
    }

    function selectAllStmt() {
        return $this->selectAllStmt;
    }

    function getBooksByGenre ($genre) {
        $con = $this->getConnectionManager();
        $selectStmt = "SELECT a.* from book a, has_genre b where a.uri = b.book_uri and b.genre_uri = " ."\"" . $genre .  "\"";
        $books = $con->executeSelectStatement($selectStmt, array());
        #print $selectStmt;
        return $this->getCollection($books);
    }

    function getNbAwards ($uri){
        $con = $this->getConnectionManager();
        $selectStmt = "SELECT a.* from wins_award a where a.book_uri = " ."\"" . $uri .  "\"";
        $awards = $con->executeSelectStatement($selectStmt, array());
        return count($awards);
    }


}


?>
