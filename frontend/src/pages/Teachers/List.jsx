import React, { useState, useEffect } from 'react';
import { getTeachers, createTeacher, updateTeacher, deleteTeacher } from './api';
import Form from './Form';
import { Edit2, Trash2, Plus, Search, ChevronLeft, ChevronRight } from 'lucide-react';

const List = () => {
  const [teachers, setTeachers] = useState([]);
  const [pagination, setPagination] = useState({});
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(true);
  
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingTeacher, setEditingTeacher] = useState(null);

  const fetchTeachers = async () => {
    setLoading(true);
    try {
      const data = await getTeachers(page, search);
      setTeachers(data.data);
      setPagination({
        current_page: data.current_page,
        last_page: data.last_page,
        total: data.total
      });
    } catch (error) {
      console.error('Failed to fetch teachers', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    const delayDebounceFn = setTimeout(() => {
      fetchTeachers();
    }, 300);
    return () => clearTimeout(delayDebounceFn);
  }, [page, search]);

  const handleSearch = (e) => {
    setSearch(e.target.value);
    setPage(1);
  };

  const handleOpenAdd = () => {
    setEditingTeacher(null);
    setIsFormOpen(true);
  };

  const handleOpenEdit = (teacher) => {
    setEditingTeacher(teacher);
    setIsFormOpen(true);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to delete this teacher?')) {
      try {
        await deleteTeacher(id);
        fetchTeachers();
      } catch (error) {
        console.error('Failed to delete teacher', error);
      }
    }
  };

  const handleFormSubmit = async (data) => {
    try {
      if (editingTeacher) {
        await updateTeacher(editingTeacher.id, data);
      } else {
        await createTeacher(data);
      }
      setIsFormOpen(false);
      fetchTeachers();
    } catch (error) {
      console.error('Failed to save teacher', error);
      alert('Failed to save teacher. Check console for details.');
    }
  };

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
        <h1 style={{ fontSize: '1.5rem', fontWeight: 700 }}>Teachers</h1>
        <button className="btn btn-primary" onClick={handleOpenAdd}>
          <Plus size={16} /> Add Teacher
        </button>
      </div>

      <div className="card" style={{ marginBottom: '1.5rem' }}>
        <div style={{ position: 'relative', maxWidth: '300px' }}>
          <Search size={18} style={{ position: 'absolute', left: '0.75rem', top: '50%', transform: 'translateY(-50%)', color: 'var(--text-muted)' }} />
          <input 
            type="text" 
            className="form-input" 
            style={{ paddingLeft: '2.5rem' }} 
            placeholder="Search teachers..." 
            value={search}
            onChange={handleSearch}
          />
        </div>
      </div>

      <div className="table-container">
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Subject</th>
              <th>Gender</th>
              <th style={{ textAlign: 'right' }}>Actions</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan="6" style={{ textAlign: 'center', padding: '2rem' }}>Loading...</td></tr>
            ) : teachers.length === 0 ? (
              <tr><td colSpan="6" style={{ textAlign: 'center', padding: '2rem', color: 'var(--text-muted)' }}>No teachers found.</td></tr>
            ) : (
              teachers.map(teacher => (
                <tr key={teacher.id}>
                  <td style={{ fontWeight: 500 }}>{teacher.salutation} {teacher.first_name} {teacher.last_name}</td>
                  <td>{teacher.email}</td>
                  <td>{teacher.phone_number}</td>
                  <td>{teacher.subject}</td>
                  <td>{teacher.gender}</td>
                  <td style={{ textAlign: 'right' }}>
                    <button className="btn btn-secondary" style={{ padding: '0.25rem', marginRight: '0.5rem' }} onClick={() => handleOpenEdit(teacher)}>
                      <Edit2 size={16} />
                    </button>
                    <button className="btn btn-danger" style={{ padding: '0.25rem' }} onClick={() => handleDelete(teacher.id)}>
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
        initialData={editingTeacher}
      />
    </div>
  );
};

export default List;
