import React from 'react'
import ReactDOM from 'react-dom/client'
import { PageRouter } from './components/router'


import './index.css'
import { ToastContainer } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'
import NanobarComponent from '@/views/dashboard/components/nanobar'
ReactDOM.createRoot(document.getElementById("root")!).render(
  <React.StrictMode>
    <NanobarComponent />
    <ToastContainer
      position="top-right"
      autoClose={5000}
      hideProgressBar={false}
      newestOnTop={false}
      closeOnClick
      rtl={false}
      pauseOnFocusLoss
      draggable
      pauseOnHover
      theme='dark'
    />
    <PageRouter />
  </React.StrictMode>
)
