<?php

namespace session\model;
use Memcache,
	Exception;

/**
 * Session class.
 */
class Session
{
	private $memcache;

	// Session id.
	private $sessionId;

	/**
	 * Constructor.
	 */
	public function __construct($sessionId = false) {
		$this->memcache = new Memcache();
		if (!$this->memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT)) {
			throw new Exception('Could not connect to Memcache.');
		}
		if ($sessionId) {
			$this->sessionId = $sessionId;
		}
	}

	/**
	 * Creates a new session with the given data.
	 *
	 * @param array $data [optional]   Array of the session data.
	 * @return string   The generated session id.
	 */
	public function create($data = false) {
		$this->sessionId = md5(uniqid(mt_rand(), true));
		// Session is alive for 30 minutes.
		$this->memcache->set($this->sessionId, $data, 0, 1800);

		return $this->sessionId;
	}

	/**
	 * Returns the value for the given key in the session data array.
	 *
	 * @param string $key [optional]   The key of the session data value.
	 * @return mixed   The session data value for the given key, or the whole session data array.
	 */
	public function get($key = false) {
		$data = $this->memcache->get($this->sessionId);

		return $key ? $data[$key] : $data;
	}

	/**
	 * Sets a new session data key and value.
	 *
	 * @param string $key    The key of the new session data value.
	 * @param mixed $value   The new session data value.
	 * @return bool   True if it is successful, false if not.
	 */
	public function set($key, $value) {
		$data = $this->get();
		$data[$key] = $value;

		return $this->memcache->set($this->sessionId, $data, 0, 1800);
	}

	/**
	 * Refreshes the session.
	 *
	 * @return bool   True if it is successful, false if not.
	 */
	public function refresh() {
		return $this->memcache->set($this->sessionId, $this->get(), 0, 1800);
	}

	/**
	 * Destroys the session.
	 *
	 * @return bool   True if it is successful, false if not.
	 */
	public function destroy() {
		return $this->memcache->delete($this->sessionId);
	}
}
