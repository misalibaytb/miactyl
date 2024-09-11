import { Nanobar } from '@/views/dashboard/components/nanobar';
import axios from 'axios';
export const get = (...args: unknown[]) => {
    Nanobar.setProgress(25)
    // @ts-expect-error This is fine
    return axios.get(...args).then((res) => {
        Nanobar.setProgress(100)
        return res
    }).catch((err) => {
        Nanobar.setProgress(0)
        return err
    })
}
export const post = (...args: unknown[]) => {
    Nanobar.setProgress(25)
    // @ts-expect-error This is fine
    return axios.post(...args).then((res) => {
        Nanobar.setProgress(100)
        return res
    }).catch((err) => {
        Nanobar.setProgress(0)
        return err
    })
}
export const put = (...args: unknown[]) => {
    Nanobar.setProgress(25)
    // @ts-expect-error This is fine
    return axios.put(...args).then((res) => {
        Nanobar.setProgress(100)
        return res
    }).catch((err) => {
        Nanobar.setProgress(0)
        return err
    })
}
export const del = (...args: unknown[]) => {
    Nanobar.setProgress(25)
    // @ts-expect-error This is fine
    return axios.delete(...args).then((res) => {
        Nanobar.setProgress(100)
        return res
    }).catch((err) => {
        Nanobar.setProgress(0)
        return err
    })
}
export const patch = (...args: unknown[]) => {
    Nanobar.setProgress(25)
    // @ts-expect-error This is fine
    return axios.patch(...args).then((res) => {
        Nanobar.setProgress(100)
        return res
    }).catch((err) => {
        Nanobar.setProgress(0)
        return err
    })
}
export const AxiosWrapper = {
    get,
    post,
    put,
    del,
    patch
}