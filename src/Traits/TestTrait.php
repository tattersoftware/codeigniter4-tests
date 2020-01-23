<?php namespace Tatter\Tests\Traits;

/**
 * Class TestTrait
 *
 * Trait for testing
 */
trait TestTrait
{
	/**
	 * Faker instance for generating content.
	 *
	 * @var Faker\Factory
	 */
	protected static $faker;

	//--------------------------------------------------------------------
	// Staging
	//--------------------------------------------------------------------

	public function setUp(): void
	{
		parent::setUp();

		// Almost everything uses the session so go ahead and mock it globally
		$this->mockSession();

		// Load Faker if it isn't already
		if (self::$faker == null)
		{
			self::$faker = \Faker\Factory::create();
		}
	}

	public function tearDown(): void
	{
		parent::tearDown();

		// If the library was loaded then reset it
		if ($this->users)
		{
			$this->users->reset();
		}

		// Remove any test items in the cache
		foreach ($this->removeCache as $row)
		{
			$method = 'remove' . $row[0];
			$this->$method($row[1]);
		}
	}

	//--------------------------------------------------------------------
	// Mocking
	//--------------------------------------------------------------------

    /**
     * Pre-loads the mock session driver into $this->session.
     */
    protected function mockSession()
    {
        require_once CIPATH . 'tests/_support/Session/MockSession.php';
        $config = config('App');
        $this->session = new MockSession(new ArrayHandler($config, '0.0.0.0'), $config);
        \Config\Services::injectMock('session', $this->session);
    }

	/**
	 * Generates random user data.
	 *
	 * @return array
	 */
	protected function generateUser(): array
	{
		return [
			'uid'       => $this->generateUid(),
			'firstname' => self::$faker->firstName,
			'lastname'  => self::$faker->lastName,
			'email'     => self::$faker->email,
			'password'  => bin2hex(random_bytes(16)),
		];
	}

	/**
	 * Generates a random Firebase-style UID.
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	protected function generateUid(int $length = 29): string
	{
		return self::$faker->format('regexify', ['[a-zA-Z0-9]{' . $length . '}']);
	}

	/**
	 * Generates random user data.
	 *
	 * @return array
	 */
	protected function generateUser(): array
	{
		return [
			'uid'       => $this->generateUid(),
			'firstname' => self::$faker->firstName,
			'lastname'  => self::$faker->lastName,
			'email'     => self::$faker->email,
			'password'  => bin2hex(random_bytes(16)),
		];
	}
}
