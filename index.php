<?php

declare(strict_types=1);

$db = new PDO('mysql: host=localhost;dbname=market','root', '4869',[]);

$query = "CREATE TABLE IF NOT EXISTS Category (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Name TEXT
);)";

$stmt = $db->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS User (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Name TEXT
);)";
$stmt = $db->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS Product (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Name TEXT,
    Price FLOAT,
    categoryId INT,
    FOREIGN KEY (categoryId) REFERENCES Category(id)
);)";

$stmt = $db->prepare($query);
$stmt->execute();

$query = "CREATE TABLE IF NOT EXISTS Cart (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    userId INT,
    productId INT,
    FOREIGN KEY (userId) REFERENCES User(id),
    FOREIGN KEY (productId) REFERENCES Product(id)
);";

$stmt = $db->prepare($query);
$stmt->execute();
insertintocategory($db, "побутова хімія");
insertintocategory($db, "паспортний стіл");
insertintocategory($db, "побутова техніка");
insertintocategory($db, "книги");
insertintocategory($db, "розваги");
insertintocategory($db, "машини");
insertintocategory($db, "для стариганів");
insertintocategory($db, "малювання");
insertintocategory($db, "для саду");
insertintocategory($db, "бдсм");
insertintouser($db, "Петя228");//1
insertintouser($db, "Настя Мирная");//2
insertintouser($db, "Вася Задерун");//3
insertintouser($db, "Валік Туз Бубновий");//4
insertintouser($db, "Нікіта Плакса-Нитік");//5
insertintouser($db, "Ванька Встанька");//6
insertintouser($db, "Максімка Піпірка");//7
insertintouser($db, "Дініска Рідіска");//8
insertintouser($db, "Олександр Накидон під губон");//9
insertintouser($db, "Сірьожа Торпеда");//10
insertintoproduct($db,"Кастет",500,5);//1
insertintoproduct($db,"Підробний паспорт",15000,2);//2
insertintoproduct($db,"Машина",250000,6);//3
insertintoproduct($db,"Резиновий фалос",2800,10);//4
insertintoproduct($db,"Лопата",600,9);//5
insertintoproduct($db,"Наручники",2500,10);//6
insertintoproduct($db,"Біблія",0,4);//7
insertintoproduct($db,"Крісло качалка",7800,7);//8
insertintoproduct($db,"Кавоварка",6900,3);//9
insertintoproduct($db,"Містер Проппер",320,1);//10
insertintoproduct($db,"Акварель",120,8);//11
insertintocart($db,1,2);
insertintocart($db,2,5);
insertintocart($db,3,9);
insertintocart($db,4,6);
insertintocart($db,5,10);
insertintocart($db,6,8);
insertintocart($db,7,3);
insertintocart($db,8,11);
insertintocart($db,9,1);
insertintocart($db,10,7);
for($i=1; $i<=10; $i++)
{
    insertintocart($db, $i, 11-$i);
}

function insertintocategory(PDO $db,string $name)
{
    $query = "INSERT INTO Category (name) VALUES (:name)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':name',$name);
    $stmt->execute();
}
function insertintouser(PDO $db,string $name)
{
    $query = "INSERT INTO User (name) VALUES (:name)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':name',$name);
    $stmt->execute();
}
function insertintoproduct(PDO $db, string $name, float $price, int $categoryId)
{
    $query = "INSERT INTO Product (Name, Price, categoryId) VALUES (:name, :price, :categoryId)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':categoryId', $categoryId);
    $stmt->execute();
}

function insertintocart(PDO $db, int $userId,int $productId )
{
    $query = "INSERT INTO Cart (userId, productId) VALUES (:userId, :productId)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':userId', $userId);
    $stmt->bindValue(':productId', $productId);
    $stmt->execute();
}

function showallusers(PDO $db)
{
    $query = 'SELECT * FROM User';
    $stmt = $db->prepare($query);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Всі користувачі: <br>";
    foreach ($users as $user) {
        echo "ID: " . $user['id'] . ", Ім'я: " . $user['Name'] . "<br>";
    }
}
showallusers($db);

function showallitemsincart(PDO $db)
{
    $query = 'SELECT Cart.id, User.name AS user_name, Product.Name AS product_name, Category.Name AS category_name, Product.Price
              FROM Cart
              INNER JOIN User ON Cart.userId = User.id
              INNER JOIN Product ON Cart.productId = Product.id
              INNER JOIN Category ON Product.categoryId = Category.id';
    $stmt = $db->prepare($query);
    $stmt->execute();

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
        echo "User: " . $item['user_name'] . "<br>";
        echo "Product: " . $item['product_name'] . "<br>";
        echo "Category: " . $item['category_name'] . "<br>";
        echo "Price: " . $item['Price'] . "<br>";
        echo "<br>";
    }
}
echo "<br>Вся корзина: <br>";
showallitemsincart($db);

function showallitemsincartuser(PDO $db, int $userId)
{
    $query = "SELECT Cart.id, User.name AS user_name, Product.Name AS product_name, Category.Name AS category_name, Product.Price
              FROM Cart
              INNER JOIN User ON Cart.userId = User.id
              INNER JOIN Product ON Cart.productId = Product.id
              INNER JOIN Category ON Product.categoryId = Category.id 
              WHERE User.id = :userId";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
        echo "User: " . $item['user_name'] . "<br>";
        echo "Product: " . $item['product_name'] . "<br>";
        echo "Category: " . $item['category_name'] . "<br>";
        echo "Price: " . $item['Price'] . "<br>";
        echo "<br>";
    }
}
echo "<br>Корзина користувача з id 6: <br>";
showallitemsincartuser($db,6);

function getcategoriesaddedtocartbyuser(PDO $db, int $userId)
{
    $query = "SELECT DISTINCT Category.Name AS category_name
              FROM Cart
              INNER JOIN Product ON Cart.productId = Product.id
              INNER JOIN Category ON Product.categoryId = Category.id
              WHERE Cart.userId = :userId";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
        echo $item['category_name'] . "<br>";
    }
}

echo "<br>Категорії, продукти яких добавив користкувач з id 6 в корзину: <br>";
getcategoriesaddedtocartbyuser($db,6);

function getuserswhoboughtproduct(PDO $db, int $productId)
{
    $query = "SELECT DISTINCT User.*
              FROM Cart
              INNER JOIN User ON Cart.userId = User.id
              WHERE Cart.productId = :productId";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':productId', $productId);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
        echo "ID: " . $item['id'] . ", Name: " . $item['Name'] . "<br>";
    }
}

echo "<br>Користувачі, які купили товар з id 6: <br>";
getuserswhoboughtproduct($db,6);

function getcategoriesnotinusercart(PDO $db, int $userId)
{
    $query = "SELECT Category.id AS category_id, Category.Name AS category_name, Product.id AS product_id, Product.Name AS product_name
              FROM Category
              LEFT JOIN Product ON Category.id = Product.categoryId AND Product.id NOT IN (
                  SELECT productId FROM Cart WHERE userId = :userId
              )";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
        if ($item['product_id']) {
            echo "Category ID: " . $item['category_id'] . ", Category Name: " . $item['category_name'] . "<br>";
            echo "Product ID: " . $item['product_id'] . ", Product Name: " . $item['product_name'] . "<br>";
            echo "<br>";
        }
    }
}

echo "<br>категорії, продуктів якої немає в користувача з id 6 в корзині: <br>";
getcategoriesnotinusercart($db,6);

?>