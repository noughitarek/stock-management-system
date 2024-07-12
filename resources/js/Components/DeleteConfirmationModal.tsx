import React from 'react';
import Modal from 'react-modal';
import { Button } from '@headlessui/react';
import { Rubrique } from '@/types';

Modal.setAppElement('#main');
interface DeleteConfirmationModalProps {
  isOpen: boolean;
  closeModal: () => void;
  onDelete: () => void;
}
const DeleteConfirmationModal: React.FC<DeleteConfirmationModalProps> = ({ isOpen, closeModal, onDelete }) => {
  const handleDelete = () => {
    onDelete();
    closeModal();
  };

  return (
    <Modal isOpen={isOpen} onRequestClose={closeModal} className="modal">
      <div className="modal-content">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this item?</p>
        <div className="modal-buttons">
          <Button onClick={handleDelete} className="bg-red-500 text-white mr-2">
            Delete
          </Button>
          <Button onClick={closeModal} className="bg-gray-300 text-gray-700">
            Cancel
          </Button>
        </div>
      </div>
    </Modal>
  );
};

export default DeleteConfirmationModal;
