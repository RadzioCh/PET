<?php
namespace App\Pet\View;

class PetView
{

    private $petData;
    private $petUpdate;

    public function setPetUpdate($petUpdate)
    {
        $this->petUpdate = $petUpdate;
    }

    public function setPetData($petData)
    {
        $this->petData = $petData;
    }


    public function topSite()
    {
        $view = <<<HTML
            
            <!DOCTYPE html>
            <html lang="pl">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Zwierzęta</title>

                <style>
                    ul.menu {
                        list-style-type: none;
                        margin-bottom: 20px;
                        padding: 0;
                        overflow: hidden;
                        background-color: #333;
                    }

                    ul.menu li {
                        float: left;
                    }

                    ul.menu li a {
                        display: block;
                        color: white;
                        text-align: center;
                        padding: 14px 16px;
                        text-decoration: none;
                    }

                    ul.menu li a:hover {
                        background-color: #111;
                    }

                     /* Styles for petForm elements */
                     form {
                        width: 50%;
                        margin: 20px auto;
                        padding: 20px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        background-color: #f8f8f8;
                    }

                    form div {
                        margin-bottom: 15px;
                    }

                    form label {
                        display: block;
                        margin-bottom: 5px;
                        font-weight: bold;
                    }

                    form input[type="text"],
                    form input[type="number"],
                    form select {
                        width: 100%;
                        padding: 8px;
                        border: 1px solid #ccc;
                        border-radius: 3px;
                        box-sizing: border-box;
                    }

                    form select {
                        appearance: none; /* Remove default arrow */
                        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 9.586L1.707 5.293l.707-.707L6 8.172l3.586-3.586.707.707z'/%3E%3C/svg%3E");
                        background-repeat: no-repeat;
                        background-position: right 8px center;
                        background-size: 12px;
                    }

                    form button[type="submit"] {
                        background-color: #4CAF50;
                        color: white;
                        padding: 10px 15px;
                        border: none;
                        border-radius: 3px;
                        cursor: pointer;
                    }

                    form button[type="submit"]:hover {
                        background-color: #45a049;
                    }
                    /* Styles for pet list */
                    .pet-list {
                        width: 80%;
                        margin: 20px auto;
                        border-collapse: collapse;
                    }

                    .pet-list th, .pet-list td {
                        border: 1px solid #ccc;
                        padding: 8px;
                        text-align: left;
                    }

                    .pet-list th {
                        background-color: #f0f0f0;
                    }
                </style>
            </head>
            <body>

        HTML;

        return $view;
    }

    public function menu()
    {
        $view = <<<HTML
            <ul class="menu">
                <li><a href="index.php">Dodaj zwierzę</a></li>
                <li><a href="index.php?site=list_pet">Pobierz listę zwierząt</a></li>

            </ul>
        HTML;

        return $view;
    
    }


    public function petForm()
    {

        $category_id = $this->petData['category']['id'] ?? '';
        $category_name = $this->petData['category']['name'] ?? '';
        $name = $this->petData['name'] ?? '';
        $photoUrls = !empty($this->petData['photoUrls']) ? implode(',', $this->petData['photoUrls']) : '';
        $tags = '';
        if (!empty($this->petData['tags'])) {
            $tagStrings = [];
            foreach ($this->petData['tags'] as $tag) {
                $tagStrings[] = $tag['id'] . ':' . $tag['name'];
            }
            $tags = implode(',', $tagStrings);
        }
        $status = $this->petData['status'] ?? 'available';

        $selectedAvailable = $status === 'available' ? 'selected' : '';
        $selectedPending = $status === 'pending' ? 'selected' : '';
        $selectedSold = $status === 'sold' ? 'selected' : '';

        $formAction = ( !empty($this->petUpdate) ) ? 'update' : 'create';
        $formSubbmit = ( !empty($this->petUpdate) ) ? 'Aktualizuj Zwierzę' : 'Dodaj Zwierzę';

        $form = <<<HTML
            <form method="POST" action="index.php?action=$formAction"> 

                <div>
                    <label for="category_id">Kategoria ID:</label>
                    <input type="number" id="category_id" name="category_id" value="$category_id">
                </div>

                <div>
                    <label for="category_name">Nazwa Kategorii:</label>
                    <input type="text" id="category_name" name="category_name" value="$category_name">
                </div>

                <div>
                    <label for="name">Nazwa Zwierzęcia:</label>
                    <input type="text" id="name" name="name" value="$name" required>
                </div>

                <div>
                    <label for="photoUrls">Adresy Zdjęć (oddzielone przecinkami):</label>
                    <input type="text" id="photoUrls" name="photoUrls" value="$photoUrls">
                </div>

                <div>
                    <label for="tags">Tagi (ID:Nazwa, oddzielone przecinkami):</label>
                    <input type="text" id="tags" name="tags" value="$tags">
                </div>

                <div>
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="available" $selectedAvailable>Dostępny</option>
                        <option value="pending" $selectedPending>Oczekujący</option>
                        <option value="sold" $selectedSold>Sprzedany</option>
                    </select>
                </div>

                <button type="submit">$formSubbmit</button>
            </form>
        HTML;

        return $form;
    }

    public function petList($dataPet)
    {
        $form = <<<HTML
            <form method="POST" action="index.php?site=list_pet&action=pet_list"> 
                <div>
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="available" selected>Dostępny</option>
                        <option value="pending">Oczekujący</option>
                        <option value="sold">Sprzedany</option>
                    </select>
                </div>

                <button type="submit">Pobierz listę zwierzaków</button>
            </form>
        HTML;

        $petListHtml = "";
        if(is_array($dataPet) && !empty($dataPet)) {
            $petListHtml .= "<table class='pet-list'>";
            $petListHtml .= "<thead><tr><th>ID</th><th>Kategoria ID</th><th>Kategoria Nazwa</th><th>Nazwa</th><th>Zdjęcia</th><th>Tagi</th><th>Status</th><th>AKCJE</th></tr></thead>";
            $petListHtml .= "<tbody>";
            foreach($dataPet as $pet) {
                $petListHtml .= "<tr>";
                $petListHtml .= "<td>" . htmlspecialchars($pet['id']  ?? '' ) . "</td>";
                $petListHtml .= "<td>" . htmlspecialchars($pet['category']['id']  ?? '') . "</td>";
                $petListHtml .= "<td>" . htmlspecialchars($pet['category']['name'] ?? '' ) . "</td>";
                $petListHtml .= "<td>" . htmlspecialchars($pet['name']  ?? '') . "</td>";
                $petListHtml .= "<td>";
                if(is_array($pet['photoUrls']) && !empty($pet['photoUrls'])){
                    foreach($pet['photoUrls'] as $photoUrl){
                        $petListHtml .= htmlspecialchars($photoUrl  ?? '') . "<br>";
                    }
                } else {
                    $petListHtml .= "Brak zdjęć";
                }
                $petListHtml .= "</td>";
                $petListHtml .= "<td>";
                if(is_array($pet['tags']) && !empty($pet['tags'])){
                    foreach($pet['tags'] as $tag){
                        $petListHtml .= htmlspecialchars($tag['id']  ?? '') . ":" . htmlspecialchars($tag['name']  ?? '') . "<br>";
                    }
                } else {
                    $petListHtml .= "Brak tagów";
                }
                $petListHtml .= "</td>";
                $petListHtml .= "<td>" . htmlspecialchars($pet['status']  ?? '') . "</td>";
                $petListHtml .= "<td>
                
                <a href='index.php?site=update_pet&pet_data=" . json_encode($pet) . "'>Zmień</a>
                <a href='index.php?site=delete_pet&pet_id=" . $pet['id'] . "'>Usuń</a>
                
                </td>";
                $petListHtml .= "</tr>";
            }
            $petListHtml .= "</tbody>";
            $petListHtml .= "</table>";
        }

        return $form . $petListHtml;


        return $form;
    }
}
