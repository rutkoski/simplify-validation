# Simplify PHP - Validation

Validation library.

## Usage

### Validate single value/rule

	try {
		require_once('vendor/simplify/validation/lib/autoload.php');
		
		$name = 'Ford';

		$rule = new Simplify_Validation_Required('Invalid name');
		$rule->validate($name);
	}
	catch (Simplify_ValidationException $e) {
		$errors = $e->getErrors();
		
		var_dump($errors);
	}

### Validate multiple values/rules

	try {
		require_once('vendor/simplify/validation/lib/autoload.php');
		
		$data = array(
			'name' => 'Ford Prefect',
			'email' => 'ford.prefect@gmail.com',
			'gender' => 'M',
			'age' => '23',
			'message' => 'Don\'t panic', 
		);
		
		$rules = new Simplify_Validation_DataValidation();
		$rules->setRule('name', new Simplify_Validation_Required('Invalid name'));
		$rules->setRule('name', new Simplify_Validation_Length('Name too short', 3));
		$rules->setRule('email', new Simplify_Validation_Email('Invalid email!'));
		$rules->setRule('gender', new Simplify_Validation_Enum('Invalid gender!', array('M', 'F')));
		$rules->setRule('age', new Simplify_Validation_Refex('Invalid age', '/^\d{2}$/'));
		$rules->setRule('message', new Simplify_Validation_Length('Invalid message', 1, 255));
		$rules->validate($data);
	}
	catch (Simplify_ValidationException $e) {
		$errors = $e->getErrors();
		
		var_dump($errors);
	}

## Available validators

* `Simplify_Validation_Callback`

Validation using a valid php callback of the format:

	$rule = new Simplify_Validation_Callback('myValidator');

	function myValidator($value) {
		// your validation
		
		throw new Simplify_ValidationException('Don\'t panic');
	}

* `Simplify_Validation_Email` (required?)
* `Simplify_Validation_Enum` (list of valid values)
* `Simplify_Validation_Length` (min, max)
* `Simplify_Validation_Password`
* `Simplify_Validation_Regex` (regular expression)
* `Simplify_Validation_Required`
* `Simplify_Validation_StrictEqual`

Brazilian formats:

* CEP
* CNPJ 
* CPF
* Telefone

### Writing custom validators

Custom validators must implement `Simplify_ValidationInterface` or extend on the base classes.

Example:

	class CustomValidator implements Simplify_ValidationInterface
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
			throw new Simplify_ValidationException('Don\'t panic');
		}
	}
