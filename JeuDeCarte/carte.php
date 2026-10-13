<?php
class carte
{
    public function __construct(
        private string $name,
        private string $Img,
        private int $Power,
        private int $Scary,
        private int $Speed,
        private int $Defense,
        private int $Bravery,
    ) {
    }
    public function showToAttack($color = null): string
    {
        return "<form action='index.php' method='get'>
                    <div class='card' style='width: 18rem;'>
                        <img class='card-img-top' src='{$this->Img}' alt='Card image cap'>
                        <div class='card-body " . $color . "'>
                            <input type='hidden' name='attack'>
                            <h5 class='card-title'>{$this->name}</h5>                   
                            <p class='card-text'>Power: {$this->Power} <input type='radio' name='type' value='power' required></p>
                            <p class='card-text'>Scary: {$this->Scary} <input type='radio' name='type' value='scary' required></p>
                            <p class='card-text'>Speed: {$this->Speed} <input type='radio' name='type' value='speed' required></p>
                            <p class='card-text'>Defense: {$this->Defense}</p>
                            <p class='card-text'>Bravery: {$this->Bravery}</p>
                            <button class='btn btn-success' name='name' value={$this->name} type='submit'>Use this card</button>
                        </div>
                    </div>    
                </form>";
    }
    public function showToDefend($type, $card, $color = null): string
    {
        return "<form action='index.php' method='get'>
                    <div class='card' style='width: 18rem;'>
                        <img class='card-img-top' src='{$this->Img}' alt='Card image cap'>
                        <div class='card-body " . $color . "'>
                            <input type='hidden' name='defense' value={$type}>
                            <input type='hidden' name='card' value={$card}>
                            <h5 class='card-title'>{$this->name}</h5>                   
                            <p class='card-text'>Power: {$this->Power}</p>
                            <p class='card-text'>Scary: {$this->Scary}</p>
                            <p class='card-text'>Speed: {$this->Speed}</p>
                            <p class='card-text'>Defense: {$this->Defense}</p>
                            <p class='card-text'>Bravery: {$this->Bravery}</p>
                            <button class='btn btn-success' name='name' value={$this->name} type='submit'>Use this card</button>
                        </div>
                    </div>    
                </form>";
    }
    public function hiden($color = null)
    {
        return "<div class='card' style='width: 18rem;'>
                        <img class='card-img-top' src='img/dos.jpg' alt='Card image cap'>    
                            <div class='card-body " . $color . "'>
                            <h5 class='card-title'>???</h5>
                            <p class='card-text'>Power: ???</p>
                            <p class='card-text'>Scary: ???</p>
                            <p class='card-text'>Speed: ???</p>
                            <p class='card-text'>Defense:???</p>
                            <p class='card-text'>Bravery: ???</p>
                        </div>                
                    </div>    
                ";
    }
    public function attack(string $type, carte $carte)
    {
        switch (strtolower($type)) {
            case "power":
                if ($this->Power > $carte->Defense) {
                    $_SESSION['Win'] = $_SESSION['Win'] + 1;
                    return "You win!";
                } else if ($this->Power < $carte->Defense) {
                    $_SESSION['Lose'] = $_SESSION['Lose'] + 1;
                    return "You lose!";
                }
                break;
            case "scary":
                if ($this->Scary > $carte->Bravery) {
                    $_SESSION['Win'] = $_SESSION['Win'] + 1;

                    return "You win!";
                } else if ($this->Scary < $carte->Bravery) {
                    $_SESSION['Lose'] = $_SESSION['Lose'] + 1;

                    return "You lose!";
                }
                break;
            case "speed":
                if ($this->Speed > $carte->Speed) {
                    $_SESSION['Win'] = $_SESSION['Win'] + 1;
                    return "You win!";
                } else if ($this->Speed < $carte->Speed) {
                    $_SESSION['Lose'] = $_SESSION['Lose'] + 1;

                    return "You lose!";

                }
                break;
        }
        $_SESSION['Draw'] = $_SESSION['Draw'] + 1;
        return "It's a tie!";

    }
    public function Defense(string $type, carte $carte)
    {
        switch (strtolower($type)) {
            case "power":
                if ($this->Defense > $carte->Power) {
                    $_SESSION['Win'] = $_SESSION['Win'] + 1;

                    return "You win!";
                } else if ($this->Defense < $carte->Power) {
                    $_SESSION['Lose'] = $_SESSION['Lose'] + 1;

                    return "You lose!";
                }
                break;
            case "scary":
                if ($this->Bravery > $carte->Scary) {
                    $_SESSION['Win'] = $_SESSION['Win'] + 1;

                    return "You win!";
                } else if ($this->Bravery < $carte->Scary) {
                    $_SESSION['Lose'] = $_SESSION['Lose'] + 1;

                    return "You lose!";
                }
                break;
            case "speed":
                if ($this->Speed > $carte->Speed) {
                    $_SESSION['Win'] = $_SESSION['Win'] + 1;

                    return "You win!";
                } else if ($this->Speed < $carte->Speed) {
                    $_SESSION['Lose'] = $_SESSION['Lose'] + 1;

                    return "You lose!";

                }
                break;
        }
        $_SESSION['Draw'] = $_SESSION['Draw'] + 1;
        return "It's a tie!";

    }
    public function getName(): string
    {
        return $this->name;
    }
    public function bestAttack(): string
    {
        $attack = $this->Power;
        $attribut = "power";
        if ($attack < $this->Scary) {
            $attack = $this->Scary;
            $attribut = "scary";
        }
        if ($attack < $this->Speed) {
            $attack = $this->Speed;
            $attribut = "speed";
        }
        return $attribut;
    }
    public function getAttack($type): string
    {
        switch (strtolower($type)) {
            case "power":
                return $this->Power;
            case "scary":
                return $this->Scary;
            case "speed":
                return $this->Speed;
        }
        return 0;
    }
    public function getDefense($type): string
    {
        switch (strtolower($type)) {
            case "power":
                return $this->Defense;
            case "scary":
                return $this->Bravery;
            case "speed":
                return $this->Speed;
        }
        return 0;
    }
    public function showAttack($type, $color = null): string
    {
        $affichage = "
                    <div class='card' style='width: 18rem;'>
                        <img class='card-img-top' src='{$this->Img}' alt='Card image cap'>
                        <div class='card-body " . $color . "'>
                            <h5 class='card-title'>{$this->name}</h5>";
        switch (strtolower($type)) {
            case "power":
                $affichage .= "<p class='card-text'>Power: {$this->Power}</p>";
                break;
            case "scary":
                $affichage .= "<p class='card-text'>Scary: {$this->Scary}</p>";
                break;
            case "speed":
                $affichage .= "<p class='card-text'>Speed: {$this->Speed}</p>";
                break;
        }

        $affichage .= "</div></div>";
        return $affichage;
    }
    public function showDefense($type, $color = null): string
    {
        $affichage = "
                    <div class='card' style='width: 18rem;'>
                        <img class='card-img-top' src='{$this->Img}' alt='Card image cap'>
                        <div class='card-body " . $color . "'>
                            <h5 class='card-title'>{$this->name}</h5>";
        switch (strtolower($type)) {
            case "power":
                $affichage .= "<p class='card-text'>Defense: {$this->Defense}</p>";
                break;
            case "scary":
                $affichage .= "<p class='card-text'>Bravery: {$this->Bravery}</p>";
                break;
            case "speed":
                $affichage .= "<p class='card-text'>Speed: {$this->Speed}</p>";
                break;
        }

        $affichage .= "</div></div>";
        return $affichage;
    }
}




