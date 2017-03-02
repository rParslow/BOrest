<?PHP

define("DATABASE_FILE", "./database/payzen.sqlite");


// Create the IPN table 
//
function create_ipn_table() {
  $db  = new SQLite3(DATABASE_FILE);
  $db->exec(
    "CREATE TABLE IF NOT EXISTS ipn (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    status STRING,

    orderId STRING,
    xType STRING,
    shopId STRING,

    transactions BLOB,

    id0 STRING,
    lastUpdateDate0 STRING,

    full BLOB,
 
    checked STRING
    )"
  );

  $db->exec(
    "CREATE TABLE IF NOT EXISTS payments (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 

    status STRING,
    pType STRING,
    applicationVersion STRING,
    webService STRING,
    applicationProvider STRING,
    version STRING, 

    isTest STRING,
    shopId STRING,
    amount STRING,
    parentBillingTransaction STRING,
    detailedErrorCode STRING,

    answer BLOB,

    paymentMethod BLOB, 
    paymentMethodDetails BLOB,
    customer BLOB,
    additionalInfo BLOB,

    serverDateTime STRING,
    ticket STRING,
    metadata STRING 
    )"
  );
  echo DATABASE_FILE." created<br />";


// create_ipn_table 
}



