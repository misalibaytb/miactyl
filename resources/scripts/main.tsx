import React from 'react'
import ReactDOM from 'react-dom/client'
import { PageRouter } from './views/router'

import './index.css'


ReactDOM.createRoot(document.getElementById("root")!).render(
  <React.StrictMode>
    <PageRouter />
  </React.StrictMode>
)
