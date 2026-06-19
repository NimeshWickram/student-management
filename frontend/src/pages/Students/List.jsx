import React, { useState, useEffect } from 'react';
import { getStudents, createStudent, updateStudent, deleteStudent } from './api';
import Form from './Form';
import { Edit2, Trash2, Plus, Search, ChevronLeft, ChevronRight } from 'lucide-react';

const List = () => {
  const [students, setStudents] = useState([]);
  const [pagination, setPagination] = useState({});
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(true);
  
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingStudent, setEditingStudent] = useState(null);

  const fetchStudents = async () => {
    setLoading(true);
    try {
      const data = await getStudents(page, search);
      setStudents(data.data);
      setPagination({
        current_page: data.current_page,
        last_page: data.last_page,
        total: data.total
      });
    } catch (error) {
      console.error('Failed to fetch students', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    const delayDebounceFn = setTimeout(() => {
      fetchStudents();
    }, 300);
    return () => clearTimeout(delayDebounceFn);
  }, [page, search]);

  const handleSearch = (e) => {
    setSearch(e.target.value);
    setPage(1); // reset to first page on search
  };

  const handleOpenAdd = () => {
    setEditingStudent(null);
    setIsFormOpen(true);
  };

  const handleOpenEdit = (student) => {
    setEditingStudent(student);
    setIsFormOpen(true);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to delete this student?')) {
      try {
        await deleteStudent(id);
        fetchStudents();
      } catch (error) {
        console.error('Failed to delete student', error);
      }
    }
  };

  const handleFormSubmit = async (data) => {
    try {
      if (editingStudent) {
        await updateStudent(editingStudent.id, data);
      } else {
        await createStudent(data);
      }
      setIsFormOpen(false);
      fetchStudents();
    } catch (error) {
      console.error('Failed to save student', error);
      alert('Failed to save student. Check console for details.');
    }
  };

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
        <h1 style={{ fontSize: '1.5rem', fontWeight: 700 }}>Students</h1>
        <button className="btn btn-primary" onClick={handleOpenAdd}>
          <Plus size={16} /> Add Student
        </button>
      </div>

      <div className="card" style={{ marginBottom: '1.5rem' }}>
        <div style={{ position: 'relative', maxWidth: '300px' }}>
          <Search size={18} style={{ position: 'absolute', left: '0.75rem', top: '50%', transform: 'translateY(-50%)', color: 'var(--text-muted)' }} />
          <input 
            type="text" 
            className="form-input" 
            style={{ paddingLeft: '2.5rem' }} 
            placeholder="Search students..." 
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
              <th>Course</th>
              <th>Grade</th>
              <th style={{ textAlign: 'right' }}>Actions</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan="6" style={{ textAlign: 'center', padding: '2rem' }}>Loading...</td></tr>
            ) : students.length === 0 ? (
              <tr><td colSpan="6" style={{ textAlign: 'center', padding: '2rem', color: 'var(--text-muted)' }}>No students found.</td></tr>
            ) : (
              students.map(student => (
                <tr key={student.id}>
                  <td style={{ fontWeight: 500 }}>{student.first_name} {student.last_name}</td>
                  <td>{student.email}</td>
                  <td>{student.phone_number}</td>
                  <td>{student.course}</td>
                  <td>{student.grade}</td>
                  <td style={{ textAlign: 'right' }}>
                    <button className="btn btn-secondary" style={{ padding: '0.25rem', marginRight: '0.5rem' }} onClick={() => handleOpenEdit(student)}>
                      <Edit2 size={16} />
                    </button>
                    <button className="btn btn-danger" style={{ padding: '0.25rem' }} onClick={() => handleDelete(student.id)}>
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
        initialData={editingStudent}
      />
    </div>
  );
};

export default List;
