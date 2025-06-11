<?php
class MenuGenerator
{
    private $items;  //передаю СЛОВАРЬ, private HashMap<String, String> items;

    public function __construct(array $items)  // public MenuGenerator(HashMap<String, String> items){}      КОНСТРУКТОР       def __init__(self, items):
    {
        $this->items = $items;  //this.items = items; 
    }

    public function getNavMenu() 
    {
        $menu = '';   //пустая строка куда будет потом добавлятся ХТМЛ код
        foreach ($this->items as $name => $link) { // for name, link in items.items():     БЕРЕМ и ключ и его значение в ФОРИЧ цикле
            $menu .= '<li class="nav-item">';  //это тупо .= ЭТО += СНАЧАЛО В НАЧАЛО ЭТО ПОТОМ ДРУГОЕ ДОБАВИТЬ И Т.Д
            $menu .= '<a class="nav-link click-scroll" href="' . $link . '">' . $name . '</a>';
            $menu .= '</li>';
        }
        return $menu; //Возвращаем ХТМЛ код большой
    }

    public function getFooterMenu() //Все так же само только что Футера
    {
        $menu = ''; //пустая строка куда будет потом добавлятся ХТМЛ код
        foreach ($this->items as $name => $link) { // for name, link in items.items():     БЕРЕМ и ключ и его значение в ФОРИЧ цикле
            $menu .= '<li class="site-footer-link-item">'; //это тупо .= ЭТО += СНАЧАЛО В НАЧАЛО ЭТО ПОТОМ ДРУГОЕ ДОБАВИТЬ И Т.Д
            $menu .= '<a href="' . $link . '" class="site-footer-link">' . $name . '</a>';
            $menu .= '</li>';
        }
        return $menu; //Возвращаем ХТМЛ код большой
    }
}
?>
