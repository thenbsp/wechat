<?php

namespace Thenbsp\Wechat\Util;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class OptionValidator extends OptionsResolver
{
    /**
     * 验证
     */
    public function validate($options)
    {
        // PHP >= 5.5 used finally
        try {
            return $this->resolve($options);
        } catch (AccessException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (InvalidOptionsException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (MissingOptionsException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (NoSuchOptionException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (OptionDefinitionException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (UndefinedOptionsException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }
}