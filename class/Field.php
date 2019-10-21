<?php
abstract class Field {
   // = = = = = = = = = = = = = = = = = = = = 
   // Члены класса
   // - - - - -
   // Имя элемента управления
   protected $name;
   // Тип элемента управления
   protected $type;
   // Название слева от элемента управления
   protected $caption;
   // Значение элемента управления
   protected $value;
   // Обязателен ли элемент к заполнению
   protected $is_required;
   // Строка дополнительных параметров
   protected $parameters;
   // Подсказка
   protected $help;
   // Ссылка на подсказку
   protected $help_url;

   // Класс CSS
   public $css_class;
   // Стиль CSS
   public $css_style;
   // ДБ объект
   public $db;

   // = = = = = = = = = = = = = = = = = = = = 
   // Методы класса
   // - - - - -
   // Конструктор класса
   function __construct (  $name,
                           $type,
                           $caption,
                           $is_required = false,
                           $value = "",
                           $parameters = "",
                           $help = "",
                           $help_url = "") {
      $this->name          = $this->encodestring($name);
      $this->type          = $type;
      $this->caption       = $caption;
      $this->value         = $value;
      $this->is_required   = $is_required;
      $this->parameters    = $parameters;
      $this->help          = $help;
      $this->help_url      = $help_url;

      $this->db = new DB();
   }

   // Метод для проверки корректности заполнения поля
   abstract function check ();

   // Метод, возвращающий название поля и самого тега элемента
   // управления (каждый наследник должен переопределить этот метод)
   abstract function get_html ();

   // Доступ к закрытым и защищенным элементам класса
   // (только для чтения)
   public function __get($key) {
      if (isset($this->$key)) return $this->$key;
      else {
         throw new ExceptionMember($key,
            "Член ".__CLASS__."::$key не существует");
      }
   }

   // Функция перевода текста с русского языка в транслит
   protected function encodestring ($st) {
      // Сначала заменяем односимвольные фонемы.
      $st = strtr($st, "абвгдеёзиклмнопрстуфхъыэ_",
         "abvgdeeziklmnoprstufh'ye_");
      $st = strtr($st, "АБВГДЕЁЗИКЛМНОПРСТУФХЪЫЭ_",
         "ABVGDEEZIKLMNOPRSTUFH'YE_");
      // Затем - "многосимвольные".
      $st=strtr($st, 
             array(
                 "ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", 
                 "щ"=>"shch","ь"=>"'", "ю"=>"yu", "я"=>"ya",
                 "Ж"=>"ZH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"SH", 
                 "Щ"=>"SHCH","Ь"=>"'", "Ю"=>"YU", "Я"=>"YA",
                 "ї"=>"i", "Ї"=>"Yi", "є"=>"e", "Є"=>"E"
                 )
               );
      // Возвращаем результат
      return $st;
   }
}
