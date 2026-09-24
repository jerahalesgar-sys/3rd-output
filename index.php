<?php
session_start();

if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [
        ['id' => 1, 'first_name' => '', 'last_name' => ''],
        ['id' => 2, 'first_name' => 'Freen', 'last_name' => 'Sarocha'],
        ['id' => 3, 'first_name' => 'Becca', 'last_name' => 'Armstrong'],
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');

    $newId = count($_SESSION['users']) > 0 ? max(array_column($_SESSION['users'], 'id')) + 1 : 1;
    $_SESSION['users'][] = [
        'id' => $newId,
        'first_name' => $firstName,
        'last_name' => $lastName,
    ];

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idToDelete = (int)$_GET['id'];
    $_SESSION['users'] = array_filter($_SESSION['users'], function($user) use ($idToDelete) {
        return $user['id'] !== $idToDelete;
    });
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My First PHP Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 40px;
            color: #000;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .form-container {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .form-container input[type="text"] {
            padding: 8px 12px;
            font-size: 16px;
            border: 1.5px solid #000;
            border-radius: 4px;
            width: 200px;
            outline: none;
        }

        .form-container button {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 8px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #2563eb;
        }

        table {
            border-collapse: collapse;
            width: 450px;
            text-align: center;
        }

        th, td {
            border: 1px solid #e5e7eb;
            padding: 10px 15px;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        td {
            font-size: 15px;
        }

        .action-link {
            text-decoration: none;
            font-weight: normal;
        }

        .action-link.edit {
            color: #3b82f6;
        }

        .action-link.delete {
            color: #ef4444;
        }

        .divider {
            color: #9ca3af;
            margin: 0 4px;
        }
    </style>
</head>
<body>

    <h1>My First PHP Page</h1>

    <form class="form-container" method="POST" action="">
        <input type="text" name="first_name" placeholder="" />
        <input type="text" name="last_name" placeholder="" />
        <button type="submit" name="submit">submit</button>
    </form>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">ID</th>
                <th style="width: 30%;">First Name</th>
                <th style="width: 30%;">Last Name</th>
                <th style="width: 25%;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($_SESSION['users'])): ?>
                <?php foreach ($_SESSION['users'] as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['first_name']) ?></td>
                        <td><?= htmlspecialchars($user['last_name']) ?></td>
                        <td>
                            <a href="?action=edit&id=<?= $user['id'] ?>" class="action-link edit">Edit</a>
                            <span class="divider">|</span>
                            <a href="?action=delete&id=<?= $user['id'] ?>" class="action-link delete" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

