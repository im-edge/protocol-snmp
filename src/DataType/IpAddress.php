<?php

namespace IMEdge\Snmp\DataType;

use InvalidArgumentException;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\OctetString as AsnType;
use Sop\ASN1\Type\Tagged\ApplicationType;
use Sop\ASN1\Type\UnspecifiedType;

class IpAddress extends DataType
{
    protected int $tag = self::IP_ADDRESS;

    final protected function __construct(ApplicationType $app)
    {
        $tag = $this->getTag();
        $binaryIp = $app->asImplicit(Element::TYPE_OCTET_STRING, $tag)->asOctetString()->string();

        if (strlen($binaryIp) !== 4) {
            throw new InvalidArgumentException(sprintf(
                '0x%s is not a valid IpAddress',
                bin2hex($binaryIp)
            ));
        }
        parent::__construct($binaryIp);
    }

    public function jsonSerialize(): array
    {
        return [
            'type'  => self::TYPE_TO_NAME_MAP[$this->tag],
            'value' => $this->getReadableValue(),
        ];
    }

    public function getReadableValue(): string
    {
        $value = AsnTypeHelper::wantString($this->rawValue);
        $readable = inet_ntop($value);
        if ($readable === false) {
            return sprintf('<not an IP address: %s>', $value);
        }

        return $readable;
    }

    public static function fromASN1(UnspecifiedType $element): static
    {
        return new static($element->asApplication());
    }

    public function toASN1(): Element
    {
        return new AsnType(AsnTypeHelper::wantString($this->rawValue));
    }
}
