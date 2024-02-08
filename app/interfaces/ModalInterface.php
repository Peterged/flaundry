<?php 
    
interface ModalHandlerInterface {
    public static function showBasicModal(string $modalName, string $modalContent): void;
    public static function showBasicModalWithHeader(string $modalName, string $modalHeader, string $modalContent): void;
}