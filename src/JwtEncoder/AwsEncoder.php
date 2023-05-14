<?php

namespace App\JwtEncoder;

use App\RequestManager\Security\SecurityRequestManager;
use Aws\Kms\KmsClient;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\HeaderAwareJWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Throwable;

/**
 * Class AwsEncoder
 * @package App\Encoder
 */
class AwsEncoder implements JWTEncoderInterface, HeaderAwareJWTEncoderInterface
{
    /**
     * @var KmsClient
     */
    private KmsClient $client;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    private SecurityRequestManager $securityRequestManager;

    /**
     * AwsEncoder constructor.
     * @param KmsClient $client
     * @param ContainerInterface $container
     * @param SecurityRequestManager $securityRequestManager
     */
    public function __construct(KmsClient $client, ContainerInterface $container, SecurityRequestManager $securityRequestManager)
    {
        $this->client = $client;
        $this->container = $container;
        $this->securityRequestManager = $securityRequestManager;
    }

    /**
     * @param array $data
     * @param array $header
     * @return string|void
     */
    public function encode(array $data, array $header = [])
    {

        $ttl = $this->container->getParameter('lexik_jwt_authentication.token_ttl');

        $token = [
            base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'RS256'])),
            base64_encode(json_encode(array_merge(["iat" => time(), "exp" => time() + $ttl], $data)))
        ];

        $signature = base64_encode($this->client->sign([
            'KeyId' => $this->securityRequestManager->findKmsKey()['key'],
            'Message' => implode('.', $token),
            'SigningAlgorithm' => 'RSASSA_PSS_SHA_256'
        ])['Signature']);

        $token[] = base64_encode($signature);

        return implode('.', $token);
    }

    /**
     * @param string $token
     * @return array|void
     * @throws JWTDecodeFailureException
     */
    public function decode($token)
    {
        $parts = explode('.', $token);

        foreach ($parts as $i => $part) {
            $parts[$i] = base64_decode($part);
        }

        $parts[0] = json_decode($parts[0], true);
        $parts[1] = json_decode($parts[1], true);

        if (count($parts) !== 3) {
            throw new JWTDecodeFailureException(JWTDecodeFailureException::INVALID_TOKEN, 'Invalid JWT Token', null);
        }

        if ($parts[1]['exp'] < time()) {
            throw new JWTDecodeFailureException(JWTDecodeFailureException::EXPIRED_TOKEN, 'Expired JWT Token', null);
        }

        $body = [
            base64_encode(json_encode($parts[0])),
            base64_encode(json_encode($parts[1]))
        ];

        try {
            $this->client->verify([
                'KeyId' => $this->securityRequestManager->findKmsKey()['key'],
                'Message' => implode('.', $body),
                'Signature' => base64_decode($parts[2]),
                'SigningAlgorithm' => 'RSASSA_PSS_SHA_256'
            ]);
        } catch (Throwable $e) {
            throw new JWTDecodeFailureException(JWTDecodeFailureException::UNVERIFIED_TOKEN, 'Unable to verify the given JWT through the given configuration. If the "lexik_jwt_authentication.encoder" encryption options have been changed since your last authentication, please renew the token. If the problem persists, verify that the configured keys/passphrase are valid.', null);
        }

        return $parts[1];
    }
}
