# Simplify Validation

Validation library.

## Usage

### Validate single value/rule

```php
try {
	$name = 'Ford';

	$rule = new \Simplify\Validation\Required('Invalid name');
	$rule->validate($name);
}
catch (\Simplify\ValidationException $e) {
	$errors = $e->getErrors();
		
	var_dump($errors);
}
```

### Validate multiple values/rules

```php
try {
	$data = array(
		'name' => 'Ford Prefect',
		'email' => 'ford.prefect@gmail.com',
		'gender' => 'M',
		'age' => '23',
		'message' => 'Don\'t panic', 
	);
		
	$rules = new \Simplify\Validation\DataValidation();
	$rules->setRule('name', new \Simplify\Validation\Required('Invalid name'));
	$rules->setRule('name', new \Simplify\Validation\Length('Name too short', 3));
	$rules->setRule('email', new \Simplify\Validation\Email('Invalid email!'));
	$rules->setRule('gender', new \Simplify\Validation\Enum('Invalid gender!', array('M', 'F')));
	$rules->setRule('age', new \Simplify\Validation\Refex('Invalid age', '/^\d{2}$/'));
	$rules->setRule('message', new \Simplify\Validation\Length('Invalid message', 1, 255));
	$rules->validate($data);
}
catch (\Simplify\ValidationException $e) {
	$errors = $e->getErrors();
	
	var_dump($errors);
}
```

#### Alternative syntax

```php
try {
    $data = array(
        'name' => 'Ford Prefect',
        'email' => 'ford.prefect@gmail.com',
        'gender' => 'M',
        'age' => '23',
        'message' => 'Don\'t panic',
    );

    $rules = new \Simplify\Validation\DataValidation(array(
        'name' => array(
            array('\Simplify\Validation\Required', 'Invalid name'),
            array('\Simplify\Validation\Length', 'Name too short', array('min' => 3))
        ),
        'email' => array('\Simplify\Validation\Email', 'Invalid email!'),
        'gender' => array('\Simplify\Validation\Enum', 'Invalid gender!', array('enum' => array('M', 'F'))),
        'age' => array('\Simplify\Validation\Refex', 'Invalid age', array('regex' => '/^\d{2}$/')),
        'message' => array('\Simplify\Validation\Length', 'Invalid message', array('min' => 1, 'max' => 255)),
    ));
    $rules->validate($data);
}
catch (\Simplify\ValidationException $e) {
    $errors = $e->getErrors();

    var_dump($errors);
}
```

## Available validators

* `\Simplify\Validation\Callback`

Validation using a valid php callback of the format:

```php
$rule = new \Simplify\Validation\Callback('myValidator');

function myValidator($value) {
	// your validation
		
	throw new \Simplify\ValidationException('Don\'t panic');
}
```

* `Simplify\Validation\Email` (required?)
* `Simplify\Validation\Enum` (list of valid values)
* `Simplify\Validation\Length` (min, max)
* `Simplify\Validation\Password`
* `Simplify\Validation\Regex` (regular expression)
* `Simplify\Validation\Required`
* `Simplify\Validation\StrictEqual`

Brazilian formats (validação de formatos brasileiros):

* CEP (validar formato de CEP)
* CNPJ
* CPF
* Telefone

### Writing custom validators

Custom validators must implement `\Simplify\ValidationInterface` or extend on the base classes.

Example:

```php
class CustomValidator implements \Simplify\ValidationInterface
{

	protected $message;

	function __construct($message)
	{
		$this->message = $message;
	}

	public function getError()
	{
		return $this->message;
	}

	public function validate($value)
	{
		// custom validation
		throw new \Simplify\ValidationException('Don\'t panic');
	}
}
```
