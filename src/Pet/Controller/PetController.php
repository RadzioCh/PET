<?php
namespace App\Pet\Controller;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;

class PetController
{

    private $get;
    private $petView;
    private $post;
    private $sendData;

    public function setPost($post)
    {
        $this->post = $post;
    }


    public function setGet($get)
    {
        $this->get = $get;
    }

    public function __construct()
    {
        $this->petView = new \App\Pet\View\PetView();
        $this->sendData = new \App\Pet\Send\SendData();

    }

    public function fire()
    {
        $view = $this->petView->topSite();
        $view .= $this->petView->menu();

        $dataPet = $this->actions();

        if(!empty($dataPet['info'])) {
            $view .= '<div style="background-color: green; color: white; padding: 10px; width: 100%;">'.$dataPet['info'].'</div>';
        }

        switch($this->get['site'])
        {
            case 'update_pet':
                $this->petView->setPetUpdate(1);
                $this->petView->setPetData(  json_decode($this->get['pet_data'], true)  );
                $view .=  $this->petView->petForm();
                break;
            case 'list_pet':
                $view .=  $this->petView->petList($dataPet);
                break;
            default:
                $view .=  $this->petView->petForm();
                break;
        }

        return $view;
    }

    private function actions()
    {
        switch($this->get['action']) {

            case 'delete_pet':
                $this->sendData->setCustomerRequest('DELETE');
                $this->sendData->setPatch('pet/'.$this->get['pet_id']);
                $this->sendData->setApiKey('apiKey');
                $response = $this->sendData->send();
                
                $data = $this->toResponse($response['http_code'], $response['response']);
                return $data;

            case 'update':
                $pet = $this->addPetValue();
                $serializer = SerializerBuilder::create()
                ->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy())
                ->build();
                $json = $serializer->serialize($pet, 'json');

                $this->sendData->setCustomerRequest('PUT');
                $this->sendData->setData($json);
                $this->sendData->setPatch('pet');
                $response = $this->sendData->send();

                $data = $this->toResponse($response['http_code'],  $response['response']);
                return $data;

            case 'pet_list':
                $petListByStatusJson = file_get_contents('https://petstore.swagger.io/v2/pet/findByStatus?status='.$this->post['status']);
                $data = json_decode($petListByStatusJson, true);
                return $data;

            case 'create':
                $pet = $this->addPetValue();
                $serializer = SerializerBuilder::create()
                ->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy())
                ->build();-
                $json = $serializer->serialize($pet, 'json');

                $this->sendData->setCustomerRequest('POST');
                $this->sendData->setData($json);
                $this->sendData->setPatch('pet');
                $response = $this->sendData->send();

                $data = $this->toResponse($response['http_code'], $response['response']);

                return $data;
        }
    }

    public function toResponse($httpCode, $response)
    {
        switch($httpCode) {
            case '500':
                echo 'Błąd 500 - prawdopodobnie brak połączenia z serwerem'; exit();
                break;
            case '400':
                echo 'Podano nieprawidłowy ID'; exit();
                break;
            case '404':
                echo 'Strona nie istnieje'; exit();
                break;
            case '405':
                echo 'Problem z danymi, popraw i spróbuj ponownie'; exit();
                break;
            case '200':
                $data = ['info' => "Operacja zakończona sukcesem"];
                break;
            default:
                dump($response);
                echo 'Nieznany błąd'; exit();
                break;
        }

        return $data;
    }

    public function addPetValue()
    {
        $category = new \App\Pet\Models\Category();
        $category->id = $this->post['category_id'];
        $category->name = $this->post['category_name'];


        $tagsExplode = explode(',', $this->post['tags']);
        $tagsArray = [];
        foreach($tagsExplode AS $valTags) {
            $dataTag = explode(':', $valTags);
            $tag = new \App\Pet\Models\Tag();
            $tag->id = $dataTag[0];
            $tag->name = $dataTag[1];
            $tagsArray[] = $tag;
        }


        // Tworzenie obiektu Pet
        $pet = new \App\Pet\Models\Pet();
        $pet->id = random_int(10000000, 99999999);
        $pet->category = $category;
        $pet->name = $this->post['name'];

        $imgUrls = explode(',', $this->post['photoUrls']);
        $pet->photoUrls = $imgUrls;
        $pet->tags = $tagsArray;
        $pet->status = $this->post['status'];

        return $pet;
    }
}

