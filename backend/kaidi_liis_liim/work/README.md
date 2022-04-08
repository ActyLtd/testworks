# PHP_homework

Created GET methode to display fruits. Additionally I have added POST methode for inserting "tree" and "fruit" vlaues via procedure that is currently not working properly. I had issues with data model and with creating many to many relational tables between trees, fruits and insects.

For running the program you should have XAMPP with MySQL database, PHP and Apache installed and SQL and Apache running. I used Postman to test the GET and POST methods. 
For GET method use URL: http://localhost/get.php?fruit_name=Yellow Banana (Yellow Banana is the variable name which you are searching by).
For POST method use URL: http://localhost/post.php with body parameters tree_name and fruit_name.

Scripts:

CREATE TABLE tree (
    tree_id int NOT NULL AUTO_INCREMENT UNIQUE,
    tree_name varchar(255) NOT NULL,
    PRIMARY KEY (tree_id)
);
ALTER TABLE tree AUTO_INCREMENT=1;

CREATE TABLE fruit (
    fruit_id int NOT NULL AUTO_INCREMENT UNIQUE,
    fruit_name varchar(255) NOT NULL,
    PRIMARY KEY (fruit_id)
);
ALTER TABLE fruit AUTO_INCREMENT=1;

CREATE TABLE insect (
    insect_id int NOT NULL AUTO_INCREMENT UNIQUE,
    insect_name varchar(255) NOT NULL,
    PRIMARY KEY (insect_id)
);
ALTER TABLE insect AUTO_INCREMENT=1;

CREATE TABLE tree_fruit (
    tree_fruit_id int NOT NULL AUTO_INCREMENT UNIQUE,
	PRIMARY KEY (tree_fruit_id),
	tree_id INT,
	fruit_id INT,
	FOREIGN KEY (tree_id) REFERENCES tree(tree_id),
	FOREIGN KEY (fruit_id) REFERENCES fruit(fruit_id)
);


DELIMITER //

CREATE PROCEDURE insertIntoMultipleTables(
IN tree_name VARCHAR(255),
IN fruit_name VARCHAR(255)
)

BEGIN   
DECLARE fruit_id1 int;
DECLARE tree_id1 int;

Insert into fruit (fruit_name) values (fruit_name);

Insert into tree (tree_name) values (tree_name);

SET tree_id1= ( SELECT tree_id from tree where tree_name = tree_name);
SET fruit_id1 =( SELECT fruit_id from fruit where fruit_name = fruit_name);

INSERT into tree_fruit(tree_id, fruit_id) values (tree_id1, fruit_id1);

END //


DELIMITER;
