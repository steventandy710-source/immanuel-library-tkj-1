<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../validations/book-validation.php";

session_start();

if (isset($_POST['store']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $pdo->beginTransaction();

        $request = $_POST;
        $errors = validateBook($request);

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $request;

            header('Location: ../../pages/books/create.php');
            exit;
        }

        $title = htmlspecialchars($request['title']);
        $isbn = htmlspecialchars($request['isbn']);
        $year = htmlspecialchars($request['year']);
        $stock = htmlspecialchars($request['stock']);
        $description = htmlspecialchars($request['description']);
        $categoryId = htmlspecialchars($request['category_id']);
        $query = "insert into books (title, isbn, year, stock, description, category_id)
                    values (:title, :isbn, :year, :stock, :description, :category_id)
                    ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'title' => $title,
            'isbn' => $isbn,
            'year' => $year,
            'stock' => $stock,
            'description' => $description,
            'category_id' => $categoryId
        ]);

        $bookId = $pdo->lastInsertId();

        $query = "insert into book_author (book_id, author_id) values (:book_id, :author_id)";
        $stmt = $pdo->prepare($query);

        $authorIds = $_POST['author_ids'];
        foreach ($authorIds as $authorId) {
            $stmt->execute([
                'book_id' => $bookId,
                'author_id' => htmlspecialchars($authorId)
            ]);
        }

        $pdo->commit();

        header('Location: ../../pages/books/index.php');
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        throw $e;
    }
}