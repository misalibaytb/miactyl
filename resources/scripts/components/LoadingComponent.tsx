export const LoadingComponent = () => {
    const loadingNames = ["loading... grabbing a coffee for you. be right back!", "loading... almost there, just untangling some wires.", "loading... adding the final sprinkles of magic.", "loading... feeding the hamsters powering this app.", "loading... still better than a dial-up connection!", "loading... because good things come to those who wait.", "loading... hold tight, we're tuning the engine.", "loading... practicing our ninja skills in the background.", "loading... counting the pixels, one by one.", "loading... your patience level is impressive!"]
    return (
        <div className="loading-container">
            <div className="loader"></div>
            {loadingNames[Math.floor(Math.random() * loadingNames.length)]}
        </div>
    )
}