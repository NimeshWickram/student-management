import React, { useState, useEffect } from 'react';
import { getSubjects, createSubject, updateSubject, deleteSubject } from './api';
import Form from './Form';
import { Edit2, Trash2, Plus, Search, ChevronLeft, ChevronRight } from 'lucide-react';

const List = () => {
  const [subjects, setSubjects] = useState([]);
  const [pagination, setPagination] = useState({});
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(true);
  
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingSubject, setEditingSubject] = useState(null);

  const fetchSubjects = async () => {
    setLoading(true);
    try {
      const data = await getSubjects(page, search);
      setSubjects(data.data);
      setPagination({
        current_page: data.current_page,
        last_page: data.last_page,
        total: data.total
      });
    } catch (error) {
      console.error('Failed to fetch subjects', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    const delayDebounceFn = setTimeout(() => {
      fetchSubjects();
    }, 300);
    return () => clearTimeout(delayDebounceFn);
  }, [page, search]);

  const handleSearch = (e) => {
    setSearch(e.target.value);
    setPage(1);
  };

  const handleOpenAdd = () => {
    setEditingSubject(null);
    setIsFormOpen(true);
  };

  const handleOpenEdit = (subject) => {
    setEditingSubject(subject);
    setIsFormOpen(true);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to delete this subject?')) {
      try {
        await deleteSubject(id);
        fetchSubjects();
      } catch (error) {
        console.error('Failed to delete subject', error);
      }
    }
  };

  const handleFormSubmit = async (data) => {
    try {
      if (editingSubject) {
        await updateSubject(editingSubject.id, data);
      } else {
        await createSubject(data);
      }
      setIsFormOpen(false);
      fetchSubjects();
    } catch (error) {
      console.error('Failed to save subject', error);
      alert('Failed to save subject. Check console for details.');
    }
  };

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
        <h1 style={{ fontSize: '1.5rem', fontWeight: 700 }}>Subjects</h1>
        <button className="btn btn-primary" onClick={handleOpenAdd}>
          <Plus size={16} /> Add Subject
        </button>
      </div>

      <div className="card" style={{ marginBottom: '1.5rem' }}>
        <div style={{ position: 'relative', maxWidth: '300px' }}>
          <Search size={18} style={{ position: 'absolute', left: '0.75rem', top: '50%', transform: 'translateY(-50%)', color: 'var(--text-muted)' }} />
          <input 
            type="text" 
            className="form-input" 
            style={{ paddingLeft: '2.5rem' }} 
            placeholder="Search subjects..." 
            value={search}
            onChange={handleSearch}
          />
        </div>
      </div>

      <div className="table-container">
        <table>
          <thead>
            <tr>
              <th>Code</th>
              <th>Name</th>
              <th>Description</th>
              <th>Credits</th>
              <th style={{ textAlign: 'right' }}>Actions</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan="5" style={{ textAlign: 'center', padding: '2rem' }}>Loading...</td></tr>
            ) : subjects.length === 0 ? (
              <tr><td colSpan="5" style={{ textAlign: 'center', padding: '2rem', color: 'var(--text-muted)' }}>No subjects found.</td></tr>
            ) : (
              subjects.map(subject => (
                <tr key={subject.id}>
                  <td style={{ fontWeight: 600 }}>{subject.code}</td>
                  <td style={{ fontWeight: 500 }}>{subject.name}</td>
                  <td><div style={{ maxWidth: '300px', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>{subject.description}</div></td>
                  <td>{subject.credits}</td>
                  <td style={{ textAlign: 'right' }}>
                    <button className="btn btn-secondary" style={{ padding: '0.25rem', marginRight: '0.5rem' }} onClick={() => handleOpenEdit(subject)}>
                      <Edit2 size={16} />
                    </button>
                    <button className="btn btn-danger" style={{ padding: '0.25rem' }} onClick={() => handleDelete(subject.id)}>
                      <Trash2 size={16} />
                    </button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {pagination.last_page > 1 && (
        <div className="pagination">
          <button 
            className="pagination-btn" 
            disabled={page === 1}
            onClick={() => setPage(prev => prev - 1)}
          ><ChevronLeft size={16} /></button>
          
          <span className="pagination-info">Page {page} of {pagination.last_page}</span>
          
          <button 
            className="pagination-btn" 
            disabled={page === pagination.last_page}
            onClick={() => setPage(prev => prev + 1)}
          ><ChevronRight size={16} /></button>
        </div>
      )}

      <Form 
        isOpen={isFormOpen} 
        onClose={() => setIsFormOpen(false)} 
        onSubmit={handleFormSubmit}
        initialData={editingSubject}
      />
    </div>
  );
};

export default List;
