<?php

namespace RussellDorgan\Capstone;
require_once("autoload.php");

use DateTime;
use Exception;
use InvalidArgumentException;
use PDO;
use PDOException;
use Ramsey\uuid\uuid;
use RangeException;
use SplFixedArray;
use TypeError;

class business implements JsonSerializable {
	use ValidateDate;
	use ValidateUuid;

	private $businessId;
	private $businessLng;
	private $businessLat;
	private $businessName;
	private $businessUrl;
	private $businessYelpId;


	public function __construct($newBusinessId, string $newBusinessLng, string $newBusinessLat, $newBusinessName, $newBusinessUrl, $newBusinessYelpId) {
		try {
			$this->setBusinessId($newBusinessId);
			$this->setBusinessLng($newBusinessLng);
			$this->setBusinessLat($newBusinessLat);
			$this->setBusinessName($newBusinessName);
			$this->setBusinessUrl($newBusinessUrl);
			$this->setBusinessYelpId($newBusinessYelpId);

		} catch(InvalidArgumentException | RangeException| Exception | TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	public function getBusinessId($businessId) {
		$this->businessId = $businessId;
	}

	public function setBusinessId($newBusinessId): void {
		try {
			$uuid = self::validateUuid($newBusinessId);
		} catch(InvalidArgumentException | RangeException | Exception | TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		$this->BusinessId = $uuid;
	}

	public function getBusinessLng() {
		$this->businessLng;
	}

	public function setBusinessLng($newBusinessLng): void {

	}

	function getBusinessLat() {
		$this->businessLat;
	}

	public function getBusinessName() {
		$this->businessName;
	}


	public function getBusinessUrl() {
		$this->businessUrl;
	}

	public function getBusinessYelpId() {
		$this->businessYelpId;
	}



	public function insert(PDO $pdo): void {

		$query = "INSERT INTO business(businessId,businessLng,businessLat,businessName,
					businessUrl, businessYelpId) VALUES(:businessId,:businessLng,:businessLat,:businessName,
					:businessUrl, :businessYelpId)";

		$statement = $pdo->prepare($query);

		$parameters = ["businessId" => $this->businessId->getBytes(), "businessLng" => $this->businessLng,
			"businessLat" => $this->businessLat, "businessName" => $this->businessName, "businessUrl" =>
				$this->businessUrl, "businessYelpId" => $this->businessYelpId];

		$statement->execute($parameters);
	}

	public function delete(PDO $pdo): void {

		$query = "DELETE FROM business WHERE businessId = :businessId";
		$statement = $pdo->prepare($query);

		$parameters = ["businessId" => $this->businessId->getBytes()];
		$statement->execute($query);

	}

	public function update(PDO $pdo): void {

		$query = "UPDATE business SET
						businessId = :businessId,
						businessLng = :businessLng,
						businessLat = :businessLat,
						businessName = :businessName,
						businessUrl = :businessUrl,
						businessYelpId = :businessYelpId,
						
						WHERE businessId = :businessId";
	}
}

$statement = $pdo->prepare($query);

$parameter = ["businessId" => $this->businessId->getBytes(),
	"businessLng" => $this->businessLng,
	"businessLat" => $this->businessLat,
	"businessName" => $this->businessName,
	"businessUrl" => $this->businessUrl,
	"businessYelpId" => $this->businessYelpId];

}

$statement->execute($parameter);

public function getBusinessbyBusinessId(PDO $pdo, $businessId): SplFixedArray {

	try {
		$businessId = self::validateUuid($businessId);
	} catch(InvalidArgumentException | RangeException | Exception | TypeError $exception) {
		throw(new PDOException($exception->getMessage(), 0, $exception));
	}

	$query = "SELECT businessId, businessLng, businessLat, businessName, businessUrl, businessYelpId FROM business WHERE businessId = :businessId";
	$statement = $pdo->prepare($query);

	$parameters = ["businessId" => $businessId->getBytes()];
	$statement->execute($parameters);

	$businessId = new SplFixedArray($statement->rowCount());
	$statement->setFetchMode(PDO::FETCH_ASSOC);
	while(($row = $statement->fetch()) !== false) {
		try {
			$businessId = new Business($row["businessId"], $row["businessLng"], $row["businessLat"], $row["businessName"], $row["businessUrl"], $row["businessYelpId"]);
			$businessId[$businessId->key()] = $businessId;
			$businessId->next();
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
	}
	return ($businessId);
}

public function jsonSerialize(): array {
	$fields = get_object_vars($this);

	$fields["businessId"] = $this->businessId->toString();
	$fields["businessLng"] = round(floatval($this->businessLng->format("")) * 1000);
	$fields["businessLat"] = round(floatval($this->businessLat->format("")) * 1000);
	$fields["businessName"] = $this->businessName->toString();
	$fields["businessUrl"] = $this->businessUrl->toString();
	$fields["businessYelpId"] = round(floatval($this->businessYelpId->format("")) * 1000);
	return ($fields);
}


}